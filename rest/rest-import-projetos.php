<?php

require_once '../init.php';

$location =  CONF_URL_SERVICE . 'rest.php';
$parameters = [];
$parameters['class']  = 'ProjetoTsService';
$parameters['method'] = 'importaProjetos';

$url = $location . '?' . http_build_query($parameters);

echo file_get_contents($url);