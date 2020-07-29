<?php

use Library\Database\Transaction;
use Services\Controllers\MotivoTSControl;

class MotivoService
{
    public static function importaMotivos()
    {
        try {
            $mt = new MotivoTSControl;
            $result = $mt->getMotivos();
            if ($result) {                
                //return $result;
                Transaction::open('bp_renegociacao');
                foreach ($result as $array) {
                    $motivo = new Motivo();
                    $motivo->fromArray($array);
                    $motivo->store();
                    unset($motivo);
                }
                Transaction::close();
                return "Success: " . count($result) . " - dados importados com sucesso!";
            } else {
                return "Error: Sem dados para importar!";
            }

        } catch (\Throwable $e) {
            return "Error: " . $e->getMessage();
        }
    }
}