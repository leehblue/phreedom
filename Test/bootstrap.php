<?php
require_once dirname(dirname(__FILE__)) . '/bootstrap.php';

// Setup database credentials
$config_file = dirname(__FILE__) . '/config.ini';
$config = parse_ini_file($config_file, true);
$db_credentials = $config['database'];