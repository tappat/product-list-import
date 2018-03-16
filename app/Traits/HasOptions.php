<?php

namespace App\Traits;

trait HasOptions
{
  function option($option)
  {
    return isset(json_decode($this->options)->$option) ? json_decode($this->options)->$option : false;
  }

  function options()
  {
    if($this->options == '') return false;
    return $this->options;
  }

  function setOption($option, $value)
  {
    if($this->options != ''){
      $options = json_decode($this->options);
    }else{
      $options = new \stdClass();
    }

    $options->$option = $value;
    $this->options = json_encode($options);
  }
}