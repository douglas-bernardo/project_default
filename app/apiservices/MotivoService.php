<?php

use Library\Database\Transaction;

class MotivoService
{
    public static function importaMotivos()
    {
        try {
            // $mt = new MotivoTSControl;
            // $result = $mt->getMotivos();
            
            // API CM
            $location = CONF_CM_SERVICE . 'resp.php';
            $parameters['class']  = 'MotivoService';
            $parameters['method'] = 'import';
            $url = $location . '?' . http_build_query($parameters);
            $result = json_decode(file_get_contents($url));
            $total = count($result->data);

            if ($result) {                
                //return $result;
                Transaction::open('bp_renegociacao');
                foreach ($result->data as $object) {
                    $array = (array) $object;
                    $motivo = new Motivo();
                    $motivo->fromArray($array);
                    $motivo->store();
                    unset($motivo);
                }
                Transaction::close();
                return "Success: " . $total . " - dados importados com sucesso!";
            } else {
                return "Error: Sem dados para importar!";
            }

        } catch (\Throwable $e) {
            return "Error: " . $e->getMessage();
        }
    }
}