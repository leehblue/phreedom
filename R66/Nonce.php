<?php

class R66_Nonce {
  
  // This is a static class and should not be instantiated
  private function __construct() {}
  
  /**
   * The salt is used to create the nonce value. 
   * 
   * The application should set the salt before generating and validating the nonce
   * 
   * @var string
   */
  public static $salt = '';
  
  /**
   * Set the security token cookie to a 30 character random string
   * 
   * @param string (Optional) $cookie_name The name for the security token cookie
   * @return string || false The security token value or false if the token could not be set
   */
  public static function set_token($cookie_name='R66_nonce_token') {
    $token = false;
    if(isset($_COOKIE[$cookie_name])) {
      $token = $_COOKIE[$cookie_name];
    }
    else {
      $token = R66_Common::rand_string(30, true);
      if(!setcookie($cookie_name, $token)) {
        $token = false;
      }
    }
    return $token;
  }
  
  public static function get_token($cookie_name='R66_nonce_token') {
    $token = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : self::set_token($cookie_name);
    return $token;
  }
  
  public static function generate($action='', $token=null, $cookie_name='R66_nonce_token') {
    if(!isset($token)) $token = self::get_token();
    $hash = md5($token . $action . self::$salt);
    $nonce = self::blend($hash, time());
    return $nonce;
  }
  
  public static function validate($nonce, $action='', $token=null, $max_seconds=1800) {
    $is_valid = false;
    if(!isset($token)) $token = self::get_token();
    $hash = md5($token . $action . self::$salt);
    $created_at = self::unblend($nonce, $hash);
    
    // is the nonce value is correct?
    $recreated_nonce = self::blend($hash, $created_at);
    if($recreated_nonce == $nonce) {
      // has the nonce expired?
      $elapsed_time = time() - $created_at;
      if($elapsed_time < $max_seconds) {
        $is_valid = true;
      }
      else {
        throw new R66_Exception_Nonce('expired nonce');
      }
    }
    else {
      throw new R66_Exception_Nonce('invalid nonce');
    }
    
    return $is_valid;
  }
  
  /**
   * Extract an unknown string from a blended string
   * 
   * Example: unblend('a1b2c3', 'abc') => '123';
   * 
   * @param string $blended_string The combined string
   * @param string $known_string The string holding the unknown string
   * @return string The unknown string
   */
  public static function unblend($blended_string, $known_string) {
    $unknown_string = '';
    $size = strlen($blended_string) - strlen($known_string);
    for($i=1; $i < $size*2; $i = $i + 2) {
      $unknown_string .= $blended_string[$i];
    }
    return $unknown_string;
  }
  
  /**
   * Insert characters of $b after every character of $a.
   * 
   * Example: abc + 123 = a1b2c3
   * 
   * @param string $a 
   * @param string $b
   * @return string
   */
  public static function blend($a, $b) {
    $a = (string) $a;
    $b = (string) $b;
    $size_a = strlen($a);
    $size_b = strlen($b);
    $size = $size_a > $size_b ? $size_a : $size_b;
    $blended_string = '';
    for($i=0; $i < $size; $i++) {
      if($i < $size_a) {
        $blended_string .= $a[$i];
      }
      if($i < $size_b) {
        $blended_string .= $b[$i];
      }
    }
    return $blended_string;
  }
  
}