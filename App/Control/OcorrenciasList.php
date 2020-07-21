<?php

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Filter;
use Livro\Database\Transaction;
use Livro\Session\Session;
use Livro\Traits\ReloadTrait;
use Livro\Widgets\Container\Card;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Datagrid\DatagridAjax;
use Livro\Widgets\Datagrid\PageNavigation;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Modal;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Hidden;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Wrapper\FormWrapperModal;

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
    private $form;

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
        $this->activeRecord = 'Ocorrencia';

        // filtro de ocorrências
        $this->filter[] = new Filter('ts_usuario_resp_id', '=', Session::getValue('user')->ts_usuario_id);
        $this->filter[] = new Filter('ts_motivo_id', 'IN', array(364, 365, 427, 683, 702, 993));
        $this->filter[] = new Filter('atendida', '=', false);

        //instancia o obj Datagrid
        $this->datagrid = new DatagridWrapper( new Datagrid );

        //instancia as colunas da Datagrid - Cabeçalho 
        $id              = new DatagridColumn('id', 'id', 'center', '');     
        $numero          = new DatagridColumn('numero_ocorrencia', 'Número', 'center', '9%');
        $data_ocorrencia = new DatagridColumn('dtocorrencia', 'Data', 'center', '10%');
        $motivo          = new DatagridColumn('descricao', 'Motivo', 'justify', '25%');
        $cliente         = new DatagridColumn('nome_cliente', 'Cliente', 'justify', '25%');
        $projeto         = new DatagridColumn('numero_projeto', 'Proj.', 'center', '4%');
        $contrato        = new DatagridColumn('numero_contrato', 'Contrato', 'center', '14%');
        $resp            = new DatagridColumn('ts_usuario_resp_nome', 'Resp.', 'center', '15%');
        $status          = new DatagridColumn('status', 'Status', 'center', '20%');

        // order to show
        $this->order_param = 'numero_ocorrencia DESC';

        //adiciona as colunas à Datagrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($numero);
        $this->datagrid->addColumn($data_ocorrencia);
        $this->datagrid->addColumn($motivo);
        $this->datagrid->addColumn($cliente);
        $this->datagrid->addColumn($projeto);
        $this->datagrid->addColumn($contrato);
        $this->datagrid->addColumn($resp);
        $this->datagrid->addColumn($status);

        // apply transformers
        $motivo->setTransformer(array($this, 'setFirstUpper'));
        $cliente->setTransformer(array($this, 'setFirstUpper'));
        $resp->setTransformer(array($this, 'setFirstUpper'));
        $status->setTransformer(array($this, 'setStatus'));
        $data_ocorrencia->setTransformer(array($this, 'formatDate'));

        // instance of action
        // $action1 = new DatagridAction( [new NegociacaoForm, 'add'] );
        // $action1->setLabel('Registrar Negociação');
        // //$action1->setClass('btn btn-info btn-sm');
        // //$action1->setStyle('font-size:10px');
        // $action1->setImage('support.png');
        // $action1->setField('numero_ocorrencia');
        // $this->datagrid->addAction($action1, '5%');


        // ******************* TESTE *********************
        $link_teste = "teste";
        $action2 = new DatagridAjax('negociacao', $link_teste, 'NegociacaoForm');
        $action2->setLabel('Registrar Negociação');
        $action2->setImage('support.png');
        $action2->setField('id');
        $this->datagrid->addAction($action2, '5%');

        // *************** FIM TESTE ***********************

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

        // *********************** TESTE *************************************

        //instancia de um formulário
        
        //$this->form = new FormWrapper(new Form('form_negociacao'), 'row');

        $this->form = new FormWrapperModal(new Form('form_negociacao'));
        $this->form->setFormTitle('Dados da Negociação');

        // hidden fields
        $usuario_id = new Hidden('usuario_id');
        $usuario_id->setEditable(false);
        $ocorrencia_id    = new Hidden('ocorrencia_id');
        $ocorrencia_id->{'id'} = 'ocorrencia_id';
        $ocorrencia_id->setEditable(false);

        $origem           = new Combo('origem_id');
        $tipo_solicitacao = new Combo('tipo_solicitacao_id');        

        Transaction::open('bp_renegociacao');
        //load origem
        $origens = Origem::all();
        $items = array();
        foreach ($origens as $obj_origem) {
            $items[$obj_origem->id] = $obj_origem->nome;
        }
        $origem->addItems($items);

        //load tipo_solicitacao
        $tipo_sol = TipoSolicitacao::all();
        $items = array();
        foreach ($tipo_sol as $obj_tp) {
            $items[$obj_tp->id] = $obj_tp->nome;
        }
        $tipo_solicitacao->addItems($items);

        Transaction::close();

        $this->form->addField('id_usuario', $usuario_id);
        $this->form->addField('id_ocorrencia', $ocorrencia_id);
        $this->form->addField('Origem', $origem);
        $this->form->addField('Tipo de Solicitação', $tipo_solicitacao);
        
        $act = new Action(array($this, 'saveNegociacao'));
        $this->form->addAction('Salvar', $act);

        // $modal = new Modal("Dados Negociação", "ModalNegociacao");
        // $modal->add($this->form);

        parent::add($this->form);

        // *********************** FIM TESTE ***********************

        //adiciona a Datagrid a página
        parent::add($card);

    }

    public function setFirstUpper($value)
    {
        $str = str_replace(' - VC', '', $value);
        $str = str_replace(' -VC', '', $str);
        $str = str_replace('-VC', '', $str);
        return ucwords(mb_strtolower($str, 'UTF-8'));
    }

    public function setStatus($value, $row)
    {
        // if ($value) {
        //     $row->children[0]->{'class'} = "datagrig-disable-link";
        //     $row->children[0]->children[0]->{'href'} = '#';
        // }
        // return ($value == 0 ? '<span class="badge badge-danger">Não atendida</span>' : '<span class="badge badge-success">Atendida</span>');
        $status = array('P' => 'Pendente', 'F' => 'Finalizado', 'C' => 'Cancelado');
        return $status[$value];
    }    

    public function formatDate($value)
    {
        return date('d-m-Y', strtotime( $value ));
    }

    public function saveNegociacao()
    {
        try{

            Transaction::open($this->connection);
            // Transaction::setLogger(new LoggerTXT('tmp/save_negociacao.txt'));
            
            $dados = $this->form->getData(); 

            $negociacao = new Negociacao();   
            //dump form data
            $negociacao->fromArray((array) $dados); 

            // additional user data
            $negociacao->usuario_id = Session::getValue('user')->id;
            $negociacao->situacao_id = 1;
            
            // persistence negociacao
            $negociacao->store(); 

            // change status ocorrencia
            $ocorrencia = new Ocorrencia($dados->ocorrencia_id);
            $ocorrencia->atendida = true;
            $ocorrencia->store();            

            Transaction::close();

            new Message('success', 'Negociação registrada - acesse "Negociações", para gerenciar suas negociações');
            $this->onReload();

        } catch(Exception $e) {
            new Message('warning', "<b>Erro:</b> " . $e->getMessage());
            Transaction::rollback();
        }
        
    }

    function show()
    {
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }
}