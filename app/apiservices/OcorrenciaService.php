<?php

use Library\Database\Transaction;
use Services\Controllers\OcorrenciaControl;

class OcorrenciaService
{
    public static function importaOcorrencias()
    {
        try {
            // $oc = new OcorrenciaControl;
            // $result = $oc->getListaOcorrencias();

            // API CM
            $location = CONF_CM_SERVICE . 'resp.php';
            $parameters['class']  = 'OcorrenciaService';
            $parameters['method'] = 'import';
            $url = $location . '?' . http_build_query($parameters);
            $result = json_decode(file_get_contents($url));
            
            if ($result) {

                if (isset($result->data->status) && $result->data->status == 'error') {
                    return $result;
                }
              
                Transaction::open('bp_renegociacao');
                foreach ($result->data as $object) {   
                    $array = (array) $object;                 
                    $ocorrencia = new Ocorrencia();
                    $ocorrencia->fromArray($array);
                    $ocorrencia->store();
                    unset($ocorrencia);
                }
                Transaction::close();

                $total = count($result->data);
                return "Success: " . $total . " dados importados com sucesso!";

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
