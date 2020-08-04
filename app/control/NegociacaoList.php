<?php

use Library\Control\Action;
use Library\Control\Page;
use Library\Traits\ReloadTrait;
use Library\Widgets\Container\Card;
use Library\Widgets\Datagrid\Datagrid;
use Library\Widgets\Datagrid\DatagridAction;
use Library\Widgets\Datagrid\DatagridColumn;
use Library\Widgets\Datagrid\PageNavigation;
use Library\Widgets\Wrapper\DatagridWrapper;

class NegociacaoList extends Page
{
    private $loaded;
    private $datagrid;

    use ReloadTrait{
        onReload as onReloadTrait;
    }

    public function __construct() 
    {
        parent::__construct();

        $this->connection   = 'bp_renegociacao';
        $this->activeRecord = 'Negociacao'; 

        $this->datagrid = new DatagridWrapper(new Datagrid);
        
        $id               = new DatagridColumn('id', 'id', 'center', '');  // hidden
        $num_ocorrencia   = new DatagridColumn('numero_ocorrencia', 'Ocorrência', 'center','10%');
        $data_ocorrencia  = new DatagridColumn('data_ocorrencia', 'Data', 'center','10%');
        $tipo_solicitacao = new DatagridColumn('tipo_solicitacao', 'Tipo', 'justify','15%');
        $cliente          = new DatagridColumn('nome_cliente', 'Cliente', 'justify', '25%');
        $proj_contrato    = new DatagridColumn('proj_contrato', 'Proj-Contrato', 'center', '15%');
        $valor_venda      = new DatagridColumn('valor_venda', 'Valor Venda', 'center', '15%');
        $situacao         = new DatagridColumn('situacao', 'Situação', 'center', '20%');

        // add columns to datagrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($num_ocorrencia);
        $this->datagrid->addColumn($data_ocorrencia);
        $this->datagrid->addColumn($tipo_solicitacao);
        $this->datagrid->addColumn($cliente);
        $this->datagrid->addColumn($proj_contrato);
        $this->datagrid->addColumn($valor_venda);
        $this->datagrid->addColumn($situacao);

        //$finalizada->setTransformer(array($this, 'setColor'));
        $cliente->setTransformer(array($this, 'setFirstUpper'));
        $data_ocorrencia->setTransformer(array($this, 'formatDate'));
        $valor_venda->setTransformer(array($this, 'setCurrence'));
        $situacao->setTransformer(array($this, 'setSituacao'));

        // actions
        $manager = new DatagridAction( [new NegociacaoForm, 'management'] );
        $manager->setLabel('Gerenciar Negociação');
        //$action1->setClass('btn btn-info btn-sm');
        //$action1->setStyle('font-size:10px');
        $manager->setImage('phone.png');
        $manager->setField('id');
        $this->datagrid->addAction($manager, '5%');

        $this->datagrid->createModel();

        // create the page navigation
        $this->pageNavigation = new PageNavigation;
        $this->pageNavigation->setAction(new Action(array($this, 'onReload')));

        // insert datagrid on card
        $card = new Card();
        $card->setHeader('Negociações');
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

    public function setSituacao($value)
    {
        return ($value == 'Aguardando Retorno' ? '<span class="badge badge-danger">'. $value .'</span>' : $value);
    }

    function show()
    {
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }

}