<?php
namespace Services\Models;

use Services\Database\ActiveRecord;

class OcorrenciaTS extends ActiveRecord
{
    private $fields = [
        "idocorrencia", 
        "status", 
        "idmotivots", 
        "dtocorrencia", 
        "idcliente",
        "nomecliente",
        "numeroprojeto",
        "numerocontrato",
        "id_us_resp as idusuarioresp"
    ];

    public function __construct() 
    {
        $sql = getStringSql('cm_ocorrencias_ts_renegociacao');        
        parent::__construct($sql, $this->fields);
    }

    public function getNumResults()
    {
        return self::$nresults;
    }

    public function getFail()
    {
        return self::$fail;
    }
}
