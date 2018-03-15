<?php
namespace App\Import;

use Carbon\Carbon;
use App\ImportPhase;
use App\Helpers\Helpers;
use App\Advertiser as AdvertiserModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class Advertiser {
  function __construct(AdvertiserModel $advertiserModel)
  {
    $this->advertiser = $advertiserModel;
    $this->doneMessage = false;
    $this->init();
    $productFile = $this->getProductData();
    if(!$productFile){ $this->end('failed', 'Product data'); return false; }
    if($this->doneMessage){ $this->end('done', $this->doneMessage); return false;}
    $this->end();
  }

  function init()
  {
    $this->importPhase = ImportPhase::initPhase('Initializing Advertiser Import', $this->advertiser->id);
  }

  function getProductData()
  {
    $downloadPhase = ImportPhase::initPhase('Getting product file', $this->advertiser->id);

    $downloadUrl = $this->advertiser->downloadProductFileUrl();
    $dstFile = Helpers::downloadProductFile($downloadUrl);
    if(!$dstFile){ ImportPhase::endPhase($downloadPhase, 'failed', 'Download'); return false; }

    $plainFile = Helpers::getPlainFile($dstFile);
    if(!$plainFile){ ImportPhase::endPhase($downloadPhase, 'failed', 'Getting plain file'); return false; }

    $this->productFileSize = Helpers::fileSize($plainFile);

    if($this->productFileSize == 0){ ImportPhase::endPhase($downloadPhase, 'failed', 'File size zero'); return false; }
    if($this->productFileSize == $this->advertiser->option('productFileSize')){ ImportPhase::endPhase($downloadPhase, 'done', 'No updates'); $this->doneMessage = 'No updates'; return true; }

    $productData = Helpers::getProductData($plainFile);
    if(!$productData){ ImportPhase::endPhase($downloadPhase, 'failed', 'Getting product data'); return false; }

    ImportPhase::endPhase($downloadPhase, 'done');
  }

  function end($status = 'done', $extraComment = false)
  {
    ImportPhase::endPhase($this->importPhase, $status, $extraComment);
  }

}