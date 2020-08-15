<?php

use Library\Control\Page;
use Library\Database\Transaction;

class ModelTest1 extends Page
{
    public function show()
    {
        try {

            Transaction::open('bp_renegociacao');

            $cl = new Cliente;
            $cl->nome          = 'Douglas';
            $cl->ts_cliente_id = 2;    
            var_dump($cl);

            echo 'Saving object...';
            
            var_dump($cl->store());

            var_dump($cl);

            Transaction::close();

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}