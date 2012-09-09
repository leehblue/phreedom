<?php

/**
 * Helper class for tweaking URLs
 */
class R66_Url {
  
  /**
   * Detect if request occurred over HTTPS and, if so, return TRUE. Otherwise return FALSE.
   * 
   * @return boolean
   */
  public static function is_https() {
    $is_https = false;
    if( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || 
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ) 
    {
      $is_https = true;
    }
    return $is_https;
  }
  
  
  public static function get_current_page_url() {
    $protocol = 'http://';
    if(self::is_https()) {
      $protocol = 'https://';
    }
    $url = $protocol . $_SERVER['HTTP_HOST'] . htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
    return $url;
  }
  
  /**
   * Attach a string of name/value pairs to a URL for the current page
   * This function looks for the presence of a ? and appropriately appends the new parameters.
   * Return a URL for the current page with the appended params.
   * 
   * @return string
   */
  public function append_query_string($nv_pairs) {
    $url = self::get_current_page_url();
    $url .= strpos($url, '?') ? '&' : '?';
    $url .= $nv_pairs;
    return $url;
  }

  /**
   * Replace the query string for the current page url
   * 
   * @param string Name value pairs formatted as name1=value1&name2=value2
   * @return string The URL to the current page with the given query string
   */
  public function replace_query_string($nv_pairs=false) {
    $url = explode('?', self::get_current_page_url());
    $url = $url[0];
    if($nv_pairs) {
      $url .= '?' . $nv_pairs;
    }
    return $url;
  }
  
}