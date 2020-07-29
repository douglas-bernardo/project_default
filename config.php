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


/**
 * DATES
 */
 define("CONF_DATE_BR", "d-m-Y H:i:s");
 define("CONF_DATE_APP", "Y-m-d H:i:s");

/**
 * SESSION
 */
define("CONF_SES_PATH", __DIR__ . "/storage/sessions/");


/**
 * REST SERVICE
 */

 define("CONF_URL_SERVICE", "https://localhost/project-default/");