<?php

use Library\Control\Page;
use Library\Database\Transaction;
use Library\Widgets\Dialog\Message;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ValorSituacaoChart extends Page
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
            $valores = Negociacao::getValoresSituacao();
            Transaction::close();
        } catch (\Exception $e) {
            new Message('error', $e->getMessage());
            Transaction::rollback();
        }

        // vetor de parâmetros para o template
        $replaces = array();
        $replaces['title'] = 'Valor Solicitado x Situação';
        $replaces['labels'] = json_encode(array_keys($valores));
        $replaces['data']  = json_encode(array_values($valores));

        $template = $twig->render('valores_situacao.html', $replaces);

        parent::add($template);
    }
}