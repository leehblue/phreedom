<?php

class R66_Autoloader {
  
  protected $_class_path;
  
  public function __construct($paths='') {
    if(is_array($paths)) {
      $this->_class_path = $paths;
    }
    else {
      $this->_class_path = array();
    }
  }
  
  public function set_class_path(array $paths) {
    $this->_class_path = $paths;
  }
  
  public function add_path($prefix, $path) {
    $this->_class_path[$prefix] = $path;
  }
  
  public function get_class_path() {
    return $this->_class_path;
  }

  public function class_loader($class_name) {
    $name_parts = explode('_', $class_name);
    $prefix = $name_parts[0];
    if(in_array($prefix, array_keys($this->_class_path))) {
      $path = str_replace('_', DIRECTORY_SEPARATOR, $class_name);
      require $this->_class_path[$prefix] . $path . '.php';
    }
  }
  
}