<?php
namespace Services\Controllers;

use Exception;
use Livro\Widgets\Dialog\Message;
use Services\Models\Ocorrencia;

class OcorrenciaServices
{
    protected $message;

    public function getListaOcorrencias()
    {
        return (new Ocorrencia)->all();        
    }
}