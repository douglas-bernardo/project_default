<?php

use Livro\Database\Transaction;
use Services\Controllers\OcorrenciaControl;

class OcorrenciaService
{
    public static function importaOcorrencias()
    {
        try {
            $oc = new OcorrenciaControl;
            $result = $oc->getListaOcorrencias();
            
            if ($result) {
                //return $result;                
                Transaction::open('bp_renegociacao');
                foreach ($result as $array) {                    
                    $ocorrencia = new Ocorrencia();
                    $ocorrencia->fromArray($array);
                    $ocorrencia->store();
                    unset($ocorrencia);
                }
                Transaction::close();
                return "Success: " . count($result) . " dados importados com sucesso!";

            } else {
                return "Error: Sem dados para importar!";
            }            

        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public static function restTest()
    {
        return "Teste API Restful ok!";
    }
}
