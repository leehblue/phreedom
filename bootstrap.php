<?php 
date_default_timezone_set('America/New_York');

if(!defined('R66_LOG_FILE')) {
  define('R66_LOG_FILE', dirname(__FILE__) . '/log.txt');
}

function class_loader($class_name) {
  $root = dirname(__FILE__);
  $path = str_replace('_', DIRECTORY_SEPARATOR, $class_name);
  require_once($root . DIRECTORY_SEPARATOR . $path . '.php');
}

spl_autoload_register('class_loader');