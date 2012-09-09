<?php

class R66_Router {
  
  protected $_application;
  
  public function __construct(R66_Application $application) {
    $this->_application = $application;
  }
  
  public function parse_request($method) {
    $route_match = false;
    
    // Get routes for the given request method
    $routes = $this->_application->get_routes(strtolower($method));
    
    // Parse route
    $uri = $_SERVER['REQUEST_URI'];
    foreach($routes as $route) {
      if($route->match_pattern($uri)) {
        // R66_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Calling user function " . print_r($route->callback, true));
        call_user_func($route->callback);
        $route_match = true;
        break;
      }
    }
    
    if(!$route_match) {
      R66_Log::write('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Unable to find a matching route for $method request: " . $uri . ' :: Routes array :: ' . print_r($routes, true));
    }
    
    return $route_match;
  }
  
}