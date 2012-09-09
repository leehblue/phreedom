<?php

class R66_Application extends R66_Model {
  
  protected $_request_methods;
  protected $_routes;
  
  public function __construct() {
    $this->_data = array(
      'debug' => false,
      'version' => 1,
      'views' => '',
      'log_file' => '',
      'database_host' => '',
      'database_name' => '',
      'database_user' => '',
      'database_password' => ''
    );
    
    $this->_request_methods = array(
      'GET' => 'get',
      'POST' => 'post',
      'PUT' => 'put',
      'DELETE' => 'delete'
    );
    $this->_routes = array();
  }
  
  public function add_route($method, $pattern, $callback) {
    if(array_key_exists($method, $this->_request_methods)) {
      $route = new R66_Route();
      $route->pattern = $pattern;
      $route->callback = $callback;
      $method = $this->_request_methods[$method];
      $this->_routes[$method][] = $route;
      // R66_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Added route for $method :: $pattern");
    }
    else {
      R66_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Unable to add route to router because the method '$method' is invalid :: Routes array :: " . print_r($this->_routes, true));
    }
  }
  
  public function get_routes($method) {
    $routes = array();
    if(isset($this->_routes[$method]) && is_array($this->_routes[$method])) {
      $routes = $this->_routes[$method];
    }
    else {
      R66_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Unalbe to find any routes for this application with the given method :: $method :: Routes :: " . print_r($this->_routes, true));
    }
    return $routes;
  }
  
  public function run() {
    $method = $_SERVER['REQUEST_METHOD'];
    if(array_key_exists($method, $this->_request_methods)) {
      $method = $this->_request_methods[$method];
      $router = new R66_Router($this);
      $router->parse_request($method);
    }
  }
  
}