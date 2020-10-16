<?php

use Library\Control\Page;
use Library\Database\Transaction;
use Library\Widgets\Dialog\Message;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class EficienciaPerdaMensalChart extends Page
{
    public function __construct() 
    {
        parent::__construct();

        $loader = new FilesystemLoader('app/resources');
        $twig = new Environment(
                    $loader, 
                    [
                        "debug" => true,
                        "auto_reload" => true, 
                        "cache" => false
                    ]
                );
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        

        try {
            Transaction::open('bp_renegociacao');        
            $result = Negociacao::getEficienciaMensal();
            $labels  =  array_keys($result);
    
            foreach ($result as $mount => $values){
                //round( $result['eficiencia'] * 100, 2);
                $data1[] = round( $values['eficiencia'] * 100, 2);
                $data2[] = round( $values['perda_financeira'] * 100, 2);
            }
    
            Transaction::close();
        } catch (\Exception $e) {
            new Message('error', $e->getMessage());
            Transaction::rollback();
        }

        $replaces = array();

        if($result){
            $replaces['title'] = 'Eficiência x Perda Mensal';
            $replaces['labels'] = json_encode($labels);
            $replaces['eficiencia']  = json_encode($data1);
            $replaces['perda_financeira']  = json_encode($data2);        
        }

        $template = $twig->render('efi_perda_mensal.html', $replaces);

        parent::add($template);
    }
}