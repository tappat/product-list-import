<?php

namespace App\Import;

use App\Network as NetworkModel;

class Network {
  function __construct(NetworkModel $networkModel){
    $this->network = $networkModel;
  }

  function init()
  {

  }
}