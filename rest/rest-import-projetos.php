<?php

$location =  'https://localhost/project-default/rest.php';
$parameters = [];
$parameters['class']  = 'ProjetoTsService';
$parameters['method'] = 'importaProjetos';

$url = $location . '?' . http_build_query($parameters);

echo file_get_contents($url);