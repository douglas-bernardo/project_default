<?php

use Library\Database\Criteria;
use Library\Database\Filter;
use Library\Database\Repository;
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

    public static function getAll(): ? array
    {
        $items = [];
        Transaction::open('bp_renegociacao');
        //$projetos = Projeto::all();

        $repository = new Repository('Projeto');
        $criteria = new Criteria();
        $criteria->add(new Filter('flgativo', '=', 'S'));
        $criteria->setProperty('order', 'numeroprojeto');
        $projetos = $repository->load($criteria);   

        if ($projetos) {
            foreach ($projetos as $projeto) {
                $items[] = [
                    "id" => $projeto->id,
                    "info" => $projeto->numeroprojeto . ' - ' . $projeto->nomeprojeto
                ];
            }
        }
        Transaction::close(); 
        return $items;
    }

    public static function getProjetoById($request): ? array
    {
        if (!isset($request['projeto_id'])) {
            return ["error"=> "Paramenter 'projeto_id' is required"];
        }

        Transaction::open('bp_renegociacao');

        $data = [];

        $projeto_id = $request['projeto_id'];
        $projeto = new Projeto($projeto_id);
        
        $data = $projeto->toArray();

        Transaction::close();

        return $data;
    }
}