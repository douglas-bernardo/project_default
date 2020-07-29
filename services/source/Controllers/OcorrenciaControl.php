<?php
namespace Services\Controllers;

use Services\Models\OcorrenciaTS;

class OcorrenciaControl
{
    public function getListaOcorrencias()
    {
        return (new OcorrenciaTS)->all();
    }
    
}