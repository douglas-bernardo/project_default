<?php

use Library\Control\Page;
use Library\Database\Transaction;
use Library\Widgets\Dialog\Message;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ValorEmAbertoTable extends Page
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
            $result = Negociacao::getValorEmAberto();
            Transaction::close();
        } catch (\Exception $e) {
            new Message('error', $e->getMessage());
            Transaction::rollback();
        }

        $replaces = array();
        if ($result) {
            $replaces['negociadora']      = $result['negociadora'];
            $replaces['valor_solicitado'] = $result['valor_solicitado'];
            $replaces['valor_em_aberto']  = $result['valor_em_aberto'];
            $replaces['percentual']       = $result['percentual'];
        }

        $template = $twig->render('valor_em_aberto.html', $replaces);

        parent::add($template);
    }
}