<?php

use Library\Database\Transaction;

class ProjetoTsService
{
    public static function importaProjetos()
    {
        try {
            // API CM
            $location = CONF_URL_CM_SERVICE . 'resp.php';
            $parameters['class']  = 'ProjetoTsService';
            $parameters['method'] = 'getData';
            $url = $location . '?' . http_build_query($parameters);
            $result = json_decode(file_get_contents($url));
            
            if ($result) {                

                if ($result->status == 'success') {
                    if (isset($result->data->exception)) {
                        return [
                            "error" => "Houve um erro na API!", 
                            "description" => $result->data->exception->data
                        ]; 
                    }
                    Transaction::open('bp_renegociacao');
                    foreach ($result->data as $object) {
                        $array = (array) $object;
                        $motivo = new Projeto();
                        $motivo->fromArray($array);
                        $motivo->store();
                        unset($motivo);
                    }
                    Transaction::close();
                    $total = count($result->data);
                    return [
                        "success"=> $total . " dados importados com sucesso!"
                    ];
                } else {
                    return $result->data;
                }
 
            } else {
                return ["error" => "sem dados para importar!"];
            }

        } catch (\Throwable $e) {
            return ["error" => $e->getMessage()];
        }
    }
}