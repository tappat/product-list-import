<?php

namespace App\Import;

use App\Option;
use Carbon\Carbon;
use App\Advertiser as AdvertiserModel;
use App\Import as ImportModel;
use Illuminate\Support\Facades\Config;

class Import {
  public function __construct($run = false)
  {
    if($run == true){
      $this->fullRun();
    }
  }

  function fullRun()
  {
    $this->init()->setup();
    $this->run();
    $this->end();
  }

  function init()
  {
    $this->importModel = ImportModel::create([
      'start_time' => Carbon::now(),
      'running' => true
    ]);
    $this->importModel->setStatus('running');
    $this->importModel->save();
    return $this;
  }

  function setup()
  {
    $options = Option::WhereIn('slug',
    [
      'network-import-status',
      'advertiser-import-status'
    ])
    ->get()->pluck('value', 'slug');
    Config::set([
      'import' => $this->importModel,
      'options' => $options
    ]);

    $this->advertisers = AdvertiserModel::statusIs($options['advertiser-import-status'])->whereHas('network', function($q) use($options){
      $q->whereHas('status', function($m) use($options){
        $m->where('slug', $options['network-import-status']);
      });
    })->get();
    return $this;
  }

  function run()
  {
    foreach($this->advertisers as $advertiser){
      $classname = 'App\Import\Advertisers\\'.$advertiser->slug;
      //new $classname($advertiser, \Illuminate\Support\Facades\Storage::disk('temp')->path('tempfile'));
      new $classname($advertiser);
    }
  }

  function end()
  {
    $this->importModel->end_time = Carbon::now();
    $this->importModel->running = false;
    $this->importModel->duration = gmdate('H:i:s', $this->importModel->end_time->diffInSeconds($this->importModel->start_time));
    $this->importModel->setStatus('done');
    $this->importModel->save();
  }

}