<?php

use Library\Control\Page;
use Library\Database\Transaction;
use Library\Widgets\Dialog\Message;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class OrigemTotalChart extends Page
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
            $valores = Negociacao::getOrigemTotal();
            Transaction::close();
        } catch (\Exception $e) {
            new Message('error', $e->getMessage());
            Transaction::rollback();
        }

        // vetor de parâmetros para o template
        $replaces = array();
        $replaces['title'] = 'Principais Origens';
        $replaces['labels'] = json_encode(array_keys($valores));
        $replaces['data']  = json_encode(array_values($valores));

        $template = $twig->render('origem_total.html', $replaces);

        parent::add($template);
    }
}