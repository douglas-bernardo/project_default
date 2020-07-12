<?php
require 'environment.php';

$config = array();

if (ENVIRONMENT == 'development') {
    define("BASE_URL", "https://localhost/project-default/");
    $config['dbname'] = 'contaazul';
    $config['host'] = 'localhost';
    $config['dbuser'] = 'developer';
    $config['dbpass'] = 'developer';
} else {
    define("BASE_URL", "https://localhost/project-default/");
    // $config['dbname'] = 'contaazul';
    // $config['host'] = 'localhost';
    // $config['dbuser'] = 'root';
    // $config['dbpass'] = '';
}