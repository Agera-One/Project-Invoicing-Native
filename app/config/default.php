<?php

$file = '../app/config/config.properties';

if (!file_exists($file)) {
    echo 'config file not found';
    exit;
}

$properties = parse_ini_file($file);

define('BASEURL', $properties['base_url']);
define('DB_TYPE', $properties['db_type']);
define('DB_HOST', $properties['db_host']);
define('DB_NAME', $properties['db_name']);
define('DB_USER', $properties['db_user']);
define('DB_PASS', $properties['db_pass']);