<?php
/**
 * The R66_FlashData class manages a static array used to populate content in views.
 * 
 * For example, if saving an object fails, this class can hold the error messages that
 * you would like to have rendered in your view. Likewise, any other data you want
 * in your view can be built up and stored in this class. 
 * 
 * NOTE: The data in this class does not persist between requests. Therefore, if a redirect
 * takes place all of this data will be reset.
 */
class R66_FlashData {
  
  protected static $_data = array();
  
  private function __construct() {
    // Do not instantiate this class
  }
  
  private function init($space='default') {
    if( !(isset(self::$_data) && is_array(self::$_data)) ) {
      self::$_data = array('default' => array());
    }
    if(!isset(self::$_data[$space])) {
      self::$_data[$space] = array();
    }
  }
  
  public function clear() {
    self::$_data = array();
  }
  
  public function set($key, $value, $space='default') {
    R66_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Setting flash data :: $key :: $value :: $space");
    self::init($space);
    self::$_data[$space][$key] = $value;
  }
  
  /**
   * Only set the key to the given value if the key does not exist.
   * 
   * This function is primarily used to set default values before rendering a view
   */
  public function set_if_empty($key, $value, $space='default') {
    self::init($space);
    if(!isset(self::$_data[$space][$key])) {
      self::$_data[$space][$key] = $value;
    }
  }
  
  /**
   * Return the value associated with the given key.
   * 
   * If the key does not exist return an empty string.
   * 
   * @return mixed
   */
  public function get($key, $space='default') {
    $value = '';
    if(isset(self::$_data[$space]) && is_array(self::$_data[$space]) && isset(self::$_data[$space][$key])) {
      $value = self::$_data[$space][$key];
    }
    else {
      R66_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Unable to get flash data for key '$key' in space '$space' :: " . print_r(self::$_data, true));
    }
    return $value;
  }
  
  /**
   * Return the entire array of $_data
   * 
   * If no data has been set, return an empty array
   * 
   * @return array
   */
  public function get_all($space='default') {
    $data = array();
    self::init();
    if(isset($data[$space]) && is_array($data[$space])) {
      $data = self::$_data[$space];
    }
    return $data;
  }
  
  public function remove($key, $space='default') {
    self::init();
    if(isset(self::$_data[$space][$key])) {
      unset(self::$_data[$space][$key]);
    }
  }
  
  /**
   * Return true if there is content stored in the private $_data array
   * 
   * If no parameters are provided, return true if there is any data available at all
   * If one parameter is provided, return true if the given key exists.
   * 
   * @return boolean
   */
  public function exists($space='default') {
    $exists = false;
    if(isset(self::$_data[$space]) && is_array(self::$_data[$space])) {
      $num_args = func_num_args();
      if($num_args == 0) {
        if(count(self::$_data[$space]) > 0) {
          $exists = true;
        }
      }
      elseif($num_args == 1) {
        $key = func_get_arg(0);
        if(isset(self::$_data[$space][$key])) {
          $exists = true;
        }
      }
    }
    return $exists;
  }
  
}