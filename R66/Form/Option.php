<?php

class R66_Form_Option extends R66_Model {
    
  public function __construct() {
    $this->_data = array(
      'value' => '',
      'text' => '',
      'selected' => false
    );
  }
  
  /**
   * Set the value of one of the keys in the private $_data array.
   * 
   * @param string $key The key in the $_data array
   * @param string $value The value to assign to the key
   * @return boolean
   */
  public function __set($key, $value) {
    $success = false;
    if(is_array($this->_data) && array_key_exists($key, $this->_data)) {
      
      if($key == 'selected') {
        // Force 'selected' to be a boolean value
        $this->_data['selected'] = ($value === true) ? true : false;
      }
      else {
        $this->_data[$key] = $value;
        $success = true;
      }
      
    }
    return $success;
  }
  
}