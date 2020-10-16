<?php

use Library\Database\Transaction;

class OcorrenciaService
{
    public static function importaOcorrencias()
    {
        try {            
            Transaction::open('bp_renegociacao');
            $last_ocorrencia = (new Ocorrencia())->getLastStored();

            // API CM parameters
            $location = CONF_URL_CM_SERVICE . 'resp.php';
            $parameters['class']  = 'OcorrenciaServices';
            $parameters['method'] = 'getData';
            if ($last_ocorrencia) {
                $value    = $last_ocorrencia->numero_ocorrencia;
                $parameters['last_ocorrencia_id'] = $value;
            } else {
                $parameters['dtocorrencia'] = '01/05/2020';
            }
            
            $url = $location . '?' . http_build_query($parameters);
            $result = json_decode(file_get_contents($url));

            if ($result) {
                if ($result->status == 'success') {
                    if (isset($result->data->exception)) {
                        return ["error" => "Houve um erro na API!", 
                        "description" => $result->data->exception->data]; 
                    }
                    foreach ($result->data as $object) {   
                        $array = (array) $object;                 
                        $ocorrencia = new Ocorrencia();
                        $ocorrencia->fromArray($array);
                        $ocorrencia->store();
                        unset($ocorrencia);
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
                Transaction::close();
                return "Error: Sem dados para importar!";
            }            

        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public static function getOcorrencia($id)
    {
        if (isset($id)) {
            $id = (int) $id['id'];
            try {
                Transaction::open('bp_renegociacao');
                $ocorrencia = new Ocorrencia($id);
                $data = $ocorrencia->toArray();
                $data['nomeprojeto'] = $ocorrencia->get_produto();
                Transaction::close();
                return $data;
            } catch (Exception $e) {
                return ["error" => $e->getMessage()];
            }
        }
    }
}
