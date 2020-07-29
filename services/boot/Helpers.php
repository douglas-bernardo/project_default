<?php
/**
 * ########################
 * ###   STATEMENTS CM  ###
 * ########################
 */

 /**
  * VC-COB-WEB: verifica e retorna as consultas CM localizadas na pasta padrão 'resources'
  *
  * @param string $queryName
  * @return string|null
  */
 function getStringSql(string $queryName): ? string
 {    
    $path = __DIR__ . "/../resources/{$queryName}.sql";
    if(file_exists($path)){
        $sql = file_get_contents($path);
        return $sql;
    } else {
        return null;
    }
    
 }