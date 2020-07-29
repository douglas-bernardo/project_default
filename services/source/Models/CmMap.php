<?php
namespace Services\Models;

use Services\Database\ActiveRecord;

class CmMap extends ActiveRecord
{
    public function __construct(string $view, $param = null) 
    {
        $sql = getStringSql($view);

        $sql = str_replace($param[0], $param[1], $sql);

        parent::__construct($sql);
    }
}