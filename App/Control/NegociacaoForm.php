<?php

use Livro\Control\Action;
use Livro\Control\Page;
use Livro\Database\Transaction;
use Livro\Log\LoggerTXT;
use Livro\Session\Session;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\DateEntry;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Hidden;
use Livro\Widgets\Wrapper\FormWrapper;

class NegociacaoForm extends Page
{
    private $form;
    private $connection;
    private $activeRecord;

    public function __construct() 
    {
        parent::__construct();

        $this->connection   = 'bp_renegociacao';
        $this->activeRecord = 'Negociacao';

        //instancia de um formulário
        $this->form = new FormWrapper(new Form('form_negociacao'), 'row');
        $this->form->setFormTitle('Dados da Negociação');

        // hidden fields
        $usuario_id = new Hidden('usuario_id');
        $usuario_id->setEditable(false);
        $ocorrencia_id    = new Hidden('ocorrencia_id');
        $ocorrencia_id->setEditable(false);

        $origem           = new Combo('origem_id');
        $tipo_solicitacao = new Combo('tipo_solicitacao_id');
        $situacao         = new Combo('situacao_id');
        
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

        //load situacao
        $situacao_itens = Situacao::all();
        $items = array();
        foreach ($situacao_itens as $obj_sit) {
            $items[$obj_sit->id] = $obj_sit->nome;
        }
        $situacao->addItems($items);

        Transaction::close();

        $this->form->addField('id usuario', $usuario_id);
        $this->form->addField('id ocorrencia', $ocorrencia_id);
        $this->form->addField('Origem', $origem);
        $this->form->addField('Tipo de Solicitação', $tipo_solicitacao);
        $this->form->addField('Situação', $situacao);
        

        $this->form->addAction('Salvar', new Action(array($this, 'saveNegociacao')));

        parent::setParentAttribute('class', 'col-md-10 col-lg-8');
        parent::add($this->form);

    }

    function add ($param)
    {        
        if(isset($param['numero_ocorrencia'])){
            Transaction::open($this->connection);
            $ocorrencia = (new Ocorrencia())->loadBy('numero_ocorrencia', $param['numero_ocorrencia']);
            if ($ocorrencia) {
                Session::setValue('ocorrencia' , $ocorrencia);
            }
            Transaction::close();
        }
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