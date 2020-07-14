<?php

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Filter;
use Livro\Session\Session;
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
    private $order_param;
    protected $pageNavigation;

    use ReloadTrait{
        onReload as onReloadTrait;
    }

    public function __construct() 
    {
        parent::__construct();

        $this->connection   = 'bp_renegociacao';
        $this->activeRecord = 'Ocorrencia';
        $this->filter = new Filter('idusuarioresp', '=', Session::getValue('ts_usuario_id'));

        //instancia o obj Datagrid
        $this->datagrid = new DatagridWrapper( new Datagrid );

        //instancia as colunas da Datagrid - Cabeçalho
        $num             = new DatagridColumn('idocorrencia', 'Número', 'center', '9%');
        $data_ocorrencia = new DatagridColumn('dtocorrencia', 'Data', 'center', '10%');
        $motivo          = new DatagridColumn('descricao', 'Motivo', 'justify', '30%');
        $cliente         = new DatagridColumn('nomecliente', 'Cliente', 'justify', '30%');
        $projeto         = new DatagridColumn('numeroprojeto', 'Proj.', 'center', '5%');
        $contrato        = new DatagridColumn('numerocontrato', 'Contrato', 'center', '15%');
        $atendida        = new DatagridColumn('atendida', 'Situação', 'center', '20%');

        $this->order_param = 'idocorrencia DESC';

        //adiciona as colunas à Datagrid
        $this->datagrid->addColumn($num);
        $this->datagrid->addColumn($data_ocorrencia);
        $this->datagrid->addColumn($motivo);
        $this->datagrid->addColumn($cliente);
        $this->datagrid->addColumn($projeto);
        $this->datagrid->addColumn($contrato);
        $this->datagrid->addColumn($atendida);

        // set transformer
        $motivo->setTransformer(array($this, 'setFirstUpper'));
        $cliente->setTransformer(array($this, 'setFirstUpper'));
        $atendida->setTransformer(array($this, 'setSituacao'));
        $data_ocorrencia->setTransformer(array($this, 'formatDate'));

        // instance of action
        $action1 = new DatagridAction( [new UsersForm, 'onEdit'] );
        $action1->setLabel('Registrar Negociação');
        //$action1->setClass('btn btn-info btn-sm');
        //$action1->setStyle('font-size:10px');
        $action1->setImage('support.png');
        $action1->setField('idocorrencia');

        $this->datagrid->addAction($action1, '5%');

        //cria o modelo da Datagrid montando sua estrutura (cabeçalho)
        $this->datagrid->createModel();

        // create the page navigation
        $this->pageNavigation = new PageNavigation;
        $this->pageNavigation->setAction(new Action(array($this, 'onReload')));

        //criando um card:
        $card = new Card();
        $card->setHeader('Ocorrências Timesharing');
        $card->setBody($this->datagrid);
        $card->setFooter($this->pageNavigation);

        //adiciona a Datagrid a página
        parent::add($card);

    }

    public function setFirstUpper($value)
    {
        return ucwords(mb_strtolower($value, 'UTF-8'));
    }

    public function setSituacao($value)
    {
        return ($value == 0 ? '<span class="badge badge-danger">Não atendida</span>' : '<span class="badge badge-success">Atendida</span>');
    }

    public function formatDate($value)
    {
        return date('d-m-Y', strtotime( $value ));
    }

    function show()
    {
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }
}