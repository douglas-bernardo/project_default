<?php

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Transaction;
use Livro\Log\LoggerTXT;
use Livro\Session\Session;
use Livro\Widgets\Container\Card;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Button;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\DateEntry;
use Livro\Widgets\Form\Divider;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\FormBase;
use Livro\Widgets\Form\Hidden;
use Livro\Widgets\Form\Label;
use Livro\Widgets\Wrapper\BootstrapFormBuilder;
use Livro\Widgets\Wrapper\FormWrapper;

class NegociacaoForm extends Page
{

    /**
     * Undocumented variable
     *
     * @var Card
     */
    private $panel;

    /**
     * Undocumented variable
     *
     * @var BootstrapFormBuilder
     */
    private $form;
    private $connection;
    private $activeRecord;

    public function __construct() 
    {
        parent::__construct();

        $this->connection   = 'bp_renegociacao';
        $this->activeRecord = 'Negociacao';

        // //instancia de um formulário
        // $this->form = new FormWrapper(new Form('form_negociacao'), 'row');
        // $this->form->setFormTitle('Dados da Negociação');

        // // hidden fields
        // $usuario_id = new Hidden('usuario_id');
        // $usuario_id->setEditable(false);
        // $ocorrencia_id    = new Hidden('ocorrencia_id');
        // $ocorrencia_id->setEditable(false);

        // $origem           = new Combo('origem_id');
        // $tipo_solicitacao = new Combo('tipo_solicitacao_id');
        // $situacao         = new Combo('situacao_id');
        
        // Transaction::open('bp_renegociacao');
        // //load origem
        // $origens = Origem::all();
        // $items = array();
        // foreach ($origens as $obj_origem) {
        //     $items[$obj_origem->id] = $obj_origem->nome;
        // }
        // $origem->addItems($items);

        // //load tipo_solicitacao
        // $tipo_sol = TipoSolicitacao::all();
        // $items = array();
        // foreach ($tipo_sol as $obj_tp) {
        //     $items[$obj_tp->id] = $obj_tp->nome;
        // }
        // $tipo_solicitacao->addItems($items);

        // //load situacao
        // $situacao_itens = Situacao::all();
        // $items = array();
        // foreach ($situacao_itens as $obj_sit) {
        //     $items[$obj_sit->id] = $obj_sit->nome;
        // }
        // $situacao->addItems($items);

        // Transaction::close();

        // $this->form->addField('id usuario', $usuario_id);
        // $this->form->addField('id ocorrencia', $ocorrencia_id);
        // $this->form->addField('Origem', $origem);
        // $this->form->addField('Tipo de Solicitação', $tipo_solicitacao);
        // $this->form->addField('Situação', $situacao);
        

        // $this->form->addAction('Salvar', new Action(array($this, 'saveNegociacao')));

        // parent::setParentAttribute('class', 'col-md-10 col-lg-8');


        $this->form = new BootstrapFormBuilder('form_negociacao');
        //$this->form->setFormTitle('Negociação'); 
        
        $this->form->setDivider(new Divider('Dados do Contrato:', 'border-top: 2px solid #999')); 

        $cliente = new Entry('nome_cliente');
        $this->form->addFields( [new Label('Cliente'), $cliente ] );

        $num_contrato    = new Entry('numero_contrato');
        $integralizacao  = new Entry('integralizacao');
        $produto         = new Entry('produto');
        $data_venda      = new Entry('data_venda');
        $valor_venda     = new Entry('valor_venda');
        $pontos          = new Entry('pontos');
        $status_contrato = new Entry('status_contrato');

        $this->form->addFields( [new Label('Número Contrato'), $num_contrato, 'size' => 'col-md-6'], 
                                [new Label('Integralização'), $integralizacao, 'size' => 'col-md-6'] );
        $this->form->addFields( [new Label('Produto'), $produto ] );
        $this->form->addFields( [new Label('Data Venda'), $data_venda, 'size' => 'col-md-6'], 
                                [new Label('Valor da venda'), $valor_venda, 'size' => 'col-md-6'] );
        $this->form->addFields( [new Label('Pontos'), $pontos, 'size' => 'col-md-6'], 
                                [new Label('Status'), $status_contrato, 'size' => 'col-md-6'] );
        
        // dados da negociação
        $this->form->setDivider(new Divider('Dados Negociação:', 'border-top: 2px solid #999')); 
        
        $num_ocorrencia   = new Entry('numero_ocorrencia');
        $data_ocorrencia  = new Entry('data_ocorrencia');
        $tipo_solicitacao = new Entry('tipo_solicitacao');
        $origem           = new Entry('origem');
        $situacao         = new Entry('situacao');

        $this->form->addFields( [new Label('Número da Ocorrência'), $num_ocorrencia, 'size' => 'col-md-6'], 
                                [new Label('Data da Ocorrência'), $data_ocorrencia, 'size' => 'col-md-6'] );

        $this->form->addFields( [new Label('Motivo'), $tipo_solicitacao, 'size' => 'col-md-4'],
                                [new Label('Origem'), $origem, 'size' => 'col-md-4'],
                                [new Label('Situação'), $situacao, 'size' => 'col-md-4'] );


        $this->panel = new Card();
        $this->panel->setBody($this->form);        

        $saveNeg = $this->form->addAction('Finalizar Negociação', new Action(array($this, 'save')));

        $this->panel->setFooter($saveNeg);

        parent::add($this->panel);

    }

    function management ($param)
    {        
        if(isset($param['id'])) {            
            Transaction::open($this->connection);

            $id = $param['id'];
            $negociacao = new Negociacao($id);             

            $form_data = new stdClass;

            // dados do contrato
            $form_data->nome_cliente    = $negociacao->get_cliente();
            $form_data->numero_contrato = $negociacao->get_proj_contrato();
            $form_data->valor_venda     = number_format($negociacao->get_valor_venda(), 2, ",", ".");
            $form_data->integralizacao  = "10% (teste)";

            //dados da ocorrencia
            $form_data->numero_ocorrencia = $negociacao->get_numero_ocorrencia();
            $form_data->data_ocorrencia   = date(CONF_DATE_BR, strtotime( $negociacao->get_data_ocorrencia()));
            $form_data->tipo_solicitacao  = $negociacao->get_tipo_solicitacao();
            $form_data->origem            = $negociacao->get_origem();
            $form_data->situacao          = $negociacao->get_situacao();

            var_dump($form_data);

            $this->form->setData($form_data);

            Transaction::close();

        }
        
    }


    function save()
    {
        $dados = $this->form->getData();
        var_dump($dados);
    }

    public function saveNegociacao()
    {
        try{

            // $class = new ReflectionClass($_SESSION['ocorrencia']);
            // var_dump(
            //     $_SESSION, 
            //     $class->getMethods()
            // );
            // die;

            Transaction::open($this->connection);
            // Transaction::setLogger(new LoggerTXT('tmp/save_negociacao.txt'));
            $class = $this->activeRecord; 
            $dados = $this->form->getData(); 
            $object = new $class;   
            $object->fromArray((array) $dados); 

            //additional data
            $object->usuario_id = Session::getValue('user')->id;
            $object->ocorrencia_id = Session::getValue('ocorrencia')->id;
            $object->store(); 

            Session::getValue('ocorrencia')->atendida = true;
            Session::getValue('ocorrencia')->store();            

            Transaction::close();
            Session::unSet('ocorrencia');

            if ($this->url_save_return) {
                header("Location: {$this->url_save_return}");
            } else {
                Session::unSet('ocorrencia_id');
                new Message('success', 'Dados armazenados com sucesso');
            }
        }

        catch(Exception $e){
            new Message('warning', "<b>Erro:</b> " . $e->getMessage());
        }
    }
}