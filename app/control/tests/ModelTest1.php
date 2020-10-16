<?php

use Library\Control\Page;
use Library\Database\Transaction;
use Library\Widgets\Base\IconSVG;

class ModelTest1 extends Page
{
    public function show()
    {

        Transaction::open('bp_renegociacao');
        
        $dataset = Negociacao::getEficienciaMensal();
        $labels  = array_keys($dataset);

        foreach ($dataset as $mount => $result){
            $data1[] = $result['eficiencia'];
            $data2[] = $result['perda_financeira'];
        }
        
        echo json_encode($data2);

        var_dump(
            $dataset,
            $labels,
            $data1            
        );

        Transaction::close();

    }
}