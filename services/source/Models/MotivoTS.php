<?php
namespace Services\Models;

use Services\Database\ActiveRecord;

class MotivoTS extends ActiveRecord
{
    public function __construct() {
        $sql = getStringSql('motivots');
        parent::__construct($sql);
    }
}