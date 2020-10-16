<?php

require_once '../init.php';

$location = CONF_URL_SERVICE . 'rest.php';
$parameters = [];
$parameters['class']  = 'MotivoService';
$parameters['method'] = 'importaMotivos';

$url = $location . '?' . http_build_query($parameters);

echo file_get_contents($url);

//var_dump(json_decode(file_get_contents($url)));