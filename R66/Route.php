<?php

class R66_Route extends R66_Model {
  
  public function __construct() {
    $this->_data = array(
      'pattern' => '',
      'callback' => ''
    );
  }
  
  public function match_pattern($uri) {
    $match = true;
    $uri_segments = explode('/', $uri);
    $pattern_segments = explode('/', $this->pattern);
    for($i=0; $i < count($pattern_segments); $i++) {
      if(strpos($pattern_segments[$i], ':') === 0 && isset($uri_segments[$i])) {
        R66_FlashData::set(substr($pattern_segments[$i], 1), urldecode($uri_segments[$i]), 'request');
      }
      elseif(!isset($uri_segments[$i]) || $pattern_segments[$i] != $uri_segments[$i]) {
        $match = false;
        break;
      }
    }
    return $match;
  }
  
}