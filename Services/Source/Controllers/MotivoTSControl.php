<?php
namespace Services\Controllers;

use Services\Models\MotivoTS;

class MotivoTSControl
{
    public function getMotivos()
    {
        return (new MotivoTS)->all();
    }
}