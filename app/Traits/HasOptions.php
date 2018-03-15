<?php

namespace App\Traits;

trait HasOptions
{
  function option($option)
  {
    return isset(json_decode($this->options)->$option) ? json_decode($this->options)->$option : false;
  }

  function setOption($option, $value)
  {
    $options = json_decode($this->options);
    $options->$option = $value;
    $this->options = json_encode($options);
  }
}