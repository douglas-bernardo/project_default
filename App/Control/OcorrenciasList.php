<?php

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Traits\ReloadTrait;
use Livro\Widgets\Container\Card;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Datagrid\PageNavigation;
use Livro\Widgets\Wrapper\DatagridWrapper;


class OcorrenciasList extends Page
{

    private $loaded;
    private $filter;   
    private $criteria;
    private $connection;
    private $activeRecord;
    private $datagrid; 
    protected $pageNavigation;

    use ReloadTrait{
        onReload as onReloadTrait;
    }

    public function __construct() 
    {
        parent::__construct();

        $this->connection   = 'bp_renegociacao';
        $this->activeRecord = 'Ocorrencia';

        //instancia o obj Datagrid
        $this->datagrid = new DatagridWrapper( new Datagrid );

        //instancia as colunas da Datagrid - Cabeçalho
        $num             = new DatagridColumn('idocorrencia', 'Número', 'center', 40);
        $data_ocorrencia = new DatagridColumn('dtocorrencia', 'Data', 'center', 50);
        $projeto         = new DatagridColumn('numeroprojeto', 'Projeto', 'center', 50);
        $contrato        = new DatagridColumn('numerocontrato', 'Contrato', 'center', 50);
        $atendida        = new DatagridColumn('atendida', 'Situação', 'center', 50);

        //adiciona as colunas à Datagrid
        $this->datagrid->addColumn($num);
        $this->datagrid->addColumn($data_ocorrencia);
        $this->datagrid->addColumn($projeto);
        $this->datagrid->addColumn($contrato);
        $this->datagrid->addColumn($atendida);

        $atendida->setTransformer(array($this, 'setSituacao'));

        //instancia de uma ação
        //$action1 = new DatagridAction(array(new UsersForm, 'onEdit'));
        $action1 = new DatagridAction( [new UsersForm, 'onEdit'] );
        $action1->setLabel('Atender');
        $action1->setImage('ico_edit.png');
        $action1->setField('idocorrencia');

        // $action2 = new DatagridAction( [$this, 'onDelete'] );
        // $action2->setLabel('Deletar');
        // $action2->setImage('ico_delete.png');
        // $action2->setField('NUM_OCORRENCIA');

        $this->datagrid->addAction($action1);
        
        //$this->datagrid->addAction($action2);

        //cria o modelo da Datagrid montando sua estrutura (cabeçalho)
        $this->datagrid->createModel();

        // create the page navigation
        $this->pageNavigation = new PageNavigation;
        $this->pageNavigation->setAction(new Action(array($this, 'onReload')));

        //criando um card:
        $card = new Card();
        $card->setHeader('Ocorrências');
        $card->setBody($this->datagrid);
        $card->setFooter($this->pageNavigation);

        //adiciona a Datagrid a página
        parent::add($card);

    }

    public function setSituacao($value)
    {
        return ($value == 0 ? '<span class="badge badge-danger">Aguardando Retorno</span>' : '<span class="badge badge-success">Atendida</span>');
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