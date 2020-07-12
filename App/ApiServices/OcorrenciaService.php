<?php

use Livro\Database\Transaction;
use Services\Controllers\OcorrenciaControl;

class OcorrenciaService
{
    public static function importaOcorrencias()
    {
        try {            
            $ocorrencia_array = array();
            $oc = new OcorrenciaControl;
            $result = $oc->getListaOcorrencias();
            
            if ($result) {

                // $ocorrencia_array = $result;
                // return $ocorrencia_array;
                
                Transaction::open('bp_renegociacao');

                foreach ($result as $array) {
                    
                    $ocorrencia = new Ocorrencia();
                    $ocorrencia->fromArray($array);
                    $ocorrencia->store();
                    unset($ocorrencia);

                }

                Transaction::close();
                return count($result) . " dados importados com sucesso!";

            } else {

                return "Sem dados para importar!";

            }            

        } catch (Exception $e) {
            return "Erro: " . $e->getMessage();
        }
    }

    public static function restTest()
    {
        return "Teste API Restful ok!";
    }
}
