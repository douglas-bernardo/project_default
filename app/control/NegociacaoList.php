<?php

use Library\Control\Action;
use Library\Control\Page;
use Library\session\Session;
use Library\Traits\ReloadTrait;
use Library\Widgets\Container\Breadcrumb;
use Library\Widgets\Container\Card;
use Library\Widgets\Datagrid\Datagrid;
use Library\Widgets\Datagrid\DatagridAction;
use Library\Widgets\Datagrid\DatagridColumn;
use Library\Widgets\Datagrid\PageNavigation;
use Library\Widgets\Dialog\Message;
use Library\Widgets\Wrapper\DatagridWrapper;

class NegociacaoList extends Page
{
    private $loaded;
    private $breadcrumb;
    private $datagrid;
    private $connection;
    private $activeRecord;
    private $pageNavigation;
    private $total_registers = 0;

    use ReloadTrait{
        onReload as onReloadTrait;
    }

    public function __construct() 
    {
        parent::__construct();

        $this->connection   = 'bp_renegociacao';
        $this->activeRecord = 'Negociacao'; 

        //breadcrumb
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->addBreadCrumbItem('Negociações');
        parent::add($this->breadcrumb);

        $this->datagrid = new DatagridWrapper(new Datagrid);
        
        $id               = new DatagridColumn('id', 'id', 'center', '');  // hidden
        $num_ocorrencia   = new DatagridColumn('numero_ocorrencia', 'Ocorrência', 'center','10%');
        $data_ocorrencia  = new DatagridColumn('data_ocorrencia', 'Data', 'center','10%');
        $tipo_solicitacao = new DatagridColumn('tipo_solicitacao', 'Tipo', 'justify','14%');
        $cliente          = new DatagridColumn('nome_cliente', 'Cliente', 'justify', '24%');
        $proj_contrato    = new DatagridColumn('projeto_contrato', 'Proj-Contrato', 'center', '15%');
        $valor_venda      = new DatagridColumn('valor_venda', 'Valor Venda', 'justify', '10%');
        $situacao         = new DatagridColumn('situacao', 'Situação', 'justify', '25%');

        // add columns to datagrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($num_ocorrencia);
        $this->datagrid->addColumn($data_ocorrencia);
        $this->datagrid->addColumn($tipo_solicitacao);
        $this->datagrid->addColumn($cliente);
        $this->datagrid->addColumn($proj_contrato);
        $this->datagrid->addColumn($valor_venda);
        $this->datagrid->addColumn($situacao);

        $data_ocorrencia->setTransformer(array($this, 'formatDate'));
        $valor_venda->setTransformer(array($this, 'setCurrence'));
        $situacao->setTransformer(array($this, 'setSituacao'));

        // actions
        $manager = new DatagridAction( [new NegociacaoForm, 'management'] );
        $manager->setLabel('Gerenciar');
        //$manager->setClass('btn btn-info btn-sm');
        //$manager->setStyle('font-size:10px');
        $manager->setImage('phone.png');
        $manager->setField('id');
        $this->datagrid->addAction($manager, '5%');

        $this->datagrid->createModel();

        // create the page navigation
        $this->pageNavigation = new PageNavigation;
        $this->pageNavigation->setAction(new Action(array($this, 'onReload')));

        // insert datagrid on card
        $card = new Card();
        //$card->setHeader('Negociações');
        $card->setBody($this->datagrid);
        $card->setFooter($this->pageNavigation);

        parent::add($card);

    }

    public function setColor($value, $row)
    {
        if ($value == 'não') {
            $row->children[1]->{'style'} = "background: green";
        }
        return $value;
    }

    public function formatDate($value)
    {
        return date('d-m-Y', strtotime( $value ));
    }

    public function setFirstUpper($value)
    {
        $str = str_replace(' - VC', '', $value);
        $str = str_replace(' -VC', '', $str);
        $str = str_replace('-VC', '', $str);
        return ucwords(mb_strtolower($str, 'UTF-8'));
    }

    public function setCurrence($value)
    {
        return number_format($value, 2, ",", ".");
    }

    public function setSituacao($value, $row)
    {
        if ($value != 'Aguardando Retorno') {
            $row->children[0]->{'class'} = "datagrig-disable-link";
            $row->{'title'} = 'Negociação Concluída';
            $row->children[0]->children[0]->{'href'} = '#';
        }
        return ($value == 'Aguardando Retorno' ? '<span class="badge badge-danger">'. $value .'</span>' : $value);
    }

    public function onReload()
    {
        if (isset($_GET['success']) && $_GET['success']==true) {
            $msg = 'Negociação finalizada com sucesso!';
            new Message('success', $msg . ' - ' . Session::getValue('teste'));
        }
        
        $this->order_param = 'data_finalizacao';
        $param = $_REQUEST;
        $this->onReloadTrait($param);
    }

    public static function reload() {}

    function show()
    {
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }

}