<?php

use Livro\Control\Page;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Dialog\Message;

use Services\Controllers\OcorrenciaServices;

class OcorrenciasList extends Page
{
    private $loaded;
    private $filter;

    /** @var Datagrid */
    private $datagrid;

    public function __construct() 
    {
        parent::__construct();

    //instancia o obj Datagrid
    $this->datagrid = new DatagridWrapper( new Datagrid );

    //instancia as colunas da Datagrid - Cabeçalho
    $num             = new DatagridColumn('NUM_OCORRENCIA', 'Número', 'center', 40);
    $data_ocorrencia = new DatagridColumn('DATA_OCORRENCIA', 'Data', 'center', 50);
    $cliente_nome    = new DatagridColumn('NOMECLIENTE', 'Cliente', 'center', 200);

    //adiciona as colunas à Datagrid
    $this->datagrid->addColumn($num);
    $this->datagrid->addColumn($data_ocorrencia);
    $this->datagrid->addColumn($cliente_nome);
    

    //instancia de uma ação
    //$action1 = new DatagridAction(array(new UsersForm, 'onEdit'));
    $action1 = new DatagridAction( [new UsersForm, 'onEdit'] );
    $action1->setLabel('Atender');
    $action1->setImage('ico_edit.png');
    $action1->setField('NUM_OCORRENCIA');

    $action2 = new DatagridAction( [$this, 'onDelete'] );
    $action2->setLabel('Deletar');
    $action2->setImage('ico_delete.png');
    $action2->setField('NUM_OCORRENCIA');

    $this->datagrid->addAction($action1);
    $this->datagrid->addAction($action2);

    //cria o modelo da Datagrid montando sua estrutura (cabeçalho)
    $this->datagrid->createModel();

    //adiciona a Datagrid a página
    parent::add($this->datagrid);

    }

    public function onReload()
    {
        try {
            $oc = new OcorrenciaServices;
            $result = $oc->getListaOcorrencias();
            $this->datagrid->clear;
            if ($result) {
                foreach ($result as $array){
                    $object = (object) $array;
                    $this->datagrid->addItem($object);
                }
            }
            $this->loaded = true;
        } catch (Exception $e) {
            new Message('warning', $e->getMessage());
        }

    }

    public function onDelete()
    {

    }

    function show()
    {
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }
}