<?php

use Livro\Control\Page;
use Livro\Session\Session;
use Livro\Traits\ReloadTrait;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Wrapper\DatagridWrapper;

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

        if (!Session::getValue('logged')) {
            echo "<script language='JavaScript'> window.location = 'index.php'; </script>";
            return;
        }

        $this->connection   = 'bp_renegociacao';
        $this->activeRecord = 'Negociacao'; 

        $this->datagrid = new DatagridWrapper(new Datagrid);
        
        $ocorrencia       = new DatagridColumn('numero_ocorrencia', 'Ocorrência', 'center','10%');
        $data_ocorrencia  = new DatagridColumn('data_ocorrencia', 'Data', 'center','10%');
        $tipo_solicitacao = new DatagridColumn('tipo_solicitacao', 'Tipo', 'center','15%');
        $cliente          = new DatagridColumn('cliente', 'Cliente', 'center', '25%');
        $proj_contrato    = new DatagridColumn('proj_contrato', 'Proj-Contrato', 'center', '15%');
        $valor_venda      = new DatagridColumn('valor_venda', 'Valor Venda', 'center', '15%');
        $situacao         = new DatagridColumn('situacao', 'Situação', 'center', '20%');

        $this->datagrid->addColumn($ocorrencia);
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

        $this->datagrid->createModel();

        parent::add($this->datagrid);

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