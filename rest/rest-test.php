<?php

require '../config.php'; 

$location =  CONF_URL_SERVICE . 'rest.php';
// $parameters = [];
// $parameters['class']  = 'TestService';
// $parameters['method'] = 'getData';
// $parameters['idvendaxcontrato'] = 128386;

$parameters = [
    'class' => 'TestService',
    'method' => 'getData',
    'idvendaxcontrato' => 128386
];


$url = $location . '?' . http_build_query($parameters);


echo file_get_contents($url);

//var_dump(json_decode(file_get_contents($url)));