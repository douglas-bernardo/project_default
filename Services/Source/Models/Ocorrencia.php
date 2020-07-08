<?php
namespace Services\Models;

use Services\Database\ActiveRecord;

class Ocorrencia extends ActiveRecord
{
    public function __construct() 
    {
        $sql = getStringSql('cm_ocorrencias_ts_renegociacao');
        parent::__construct($sql);
    }

    // public function all()
    // {
    //     return $this->load();
    // }

    public function getNumResults()
    {
        return self::$nresults;
    }

    public function getFail()
    {
        return self::$fail;
    }
}
