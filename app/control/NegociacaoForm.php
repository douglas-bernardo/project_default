<?php

use Library\Control\Action;
use Library\Control\Page;
use Library\Database\Transaction;
use Library\Session\Session;
use Library\Widgets\Container\Breadcrumb;
use Library\Widgets\Container\Card;
use Library\Widgets\Container\Row;
use Library\Widgets\Dialog\Message;
use Library\Widgets\Form\Combo;
use Library\Widgets\Form\DateEntry;
use Library\Widgets\Form\Divider;
use Library\Widgets\Form\Entry;
use Library\Widgets\Form\Hidden;
use Library\Widgets\Form\Label;
use Library\Widgets\Wrapper\BootstrapFormBuilder;

class NegociacaoForm extends Page
{

    /** @var Breadcrumb */
    private $breadcrumb;

    /** @var Card */
    private $panel;

    /**
     * Undocumented variable
     *
     * @var BootstrapFormBuilder
     */
    private $form;
    private $connection;
    private $activeRecord;
    private $combo_situacao;

    public function __construct() 
    {
        parent::__construct();

        $this->connection   = 'bp_renegociacao';
        $this->activeRecord = 'Negociacao';

        $this->breadcrumb = new Breadcrumb;
        $this->breadcrumb->addBreadCrumbItem('Negociações', new Action(['NegociacaoList','reload']));
        $this->breadcrumb->addBreadCrumbItem('Gerenciar Negociação');

        parent::add($this->breadcrumb);

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
        
        $this->form->setDivider(new Divider('Dados do Contrato:', 'border-top: 2px solid #999')); 

        $cliente = new Entry('nome_cliente');
        $cliente->setOptions('sizing', 'form-control-sm border-0')->setEditable(false);

        $this->form->addFields( [new Label('Cliente'), $cliente] );

        $num_contrato    = new Entry('numero_contrato');        
        $integralizacao  = new Entry('integralizacao');        
        $produto         = new Entry('nome_projeto');        
        $data_venda      = new Entry('data_venda');
        $valor_venda     = new Entry('valor_venda');        
        $pontos          = new Entry('pontos_contrato');
        
        $num_contrato->setOptions('sizing', 'form-control-sm border-0')->setEditable(false);
        $integralizacao->setOptions('sizing', 'form-control-sm border-0')->setEditable(false);
        $produto->setOptions('sizing', 'form-control-sm border-0')->setEditable(false);
        $data_venda->setOptions('sizing', 'form-control-sm border-0')->setEditable(false);
        $valor_venda->setOptions('sizing', 'form-control-sm border-0')->setEditable(false);
        $pontos->setOptions('sizing', 'form-control-sm border-0')->setEditable(false);
        

        $this->form->addFields( [new Label('Número Contrato'), $num_contrato, 'size' => 'col-md-4'], 
                                [new Label('Integralização'), $integralizacao, 'size' => 'col-md-4'],
                                [new Label('Produto'), $produto, 'size' => 'col-md-4'] );

        //$this->form->addFields( [new Label('Produto'), $produto ] );
        
        $this->form->addFields( [new Label('Data Venda'), $data_venda, 'size' => 'col-md-4'], 
                                [new Label('Valor da venda'), $valor_venda, 'size' => 'col-md-4'],
                                [new Label('Pontos'), $pontos, 'size' => 'col-md-4'] );
        
        // dados da negociação
        $this->form->setDivider(new Divider('Dados Negociação:', 'border-top: 2px solid #999')); 
        
        $num_ocorrencia   = new Entry('numero_ocorrencia');
        $data_ocorrencia  = new Entry('data_ocorrencia');
        $tipo_solicitacao = new Entry('tipo_solicitacao');
        $origem           = new Entry('origem');
        $situacao         = new Entry('situacao');

        $num_ocorrencia->setOptions('sizing', 'form-control-sm border-0')->setEditable(false);
        $data_ocorrencia->setOptions('sizing', 'form-control-sm border-0')->setEditable(false);
        $tipo_solicitacao->setOptions('sizing', 'form-control-sm border-0')->setEditable(false);
        $origem->setOptions('sizing', 'form-control-sm border-0')->setEditable(false);
        $situacao->setOptions('sizing', 'form-control-sm border-0')->setEditable(false);

        $this->form->addFields( [new Label('Número da Ocorrência'), $num_ocorrencia, 'size' => 'col-md-2'], 
                                [new Label('Data da Ocorrência'), $data_ocorrencia, 'size' => 'col-md-2'],
                                [new Label('Motivo'), $tipo_solicitacao, 'size' => 'col-md-4'],
                                [new Label('Origem'), $origem, 'size' => 'col-md-2'],
                                [new Label('Situação'), $situacao, 'size' => 'col-md-2'] );

        $this->form->addFields([new Hidden('negociacao_id')]);     
        $this->form->addFields([new Hidden('data_finalizacao')]);                            

        //input that catch situação value
        $this->form->addFields([new Hidden('situacao_id')]);

        $this->panel = new Card();
        $this->panel->setBody($this->form);

        //load situação:
        $this->combo_situacao = new Combo('situacao_id', 'form-control', 'Selecione para finalizar...');
        Transaction::open('bp_renegociacao');
        $situacao_tipos = Situacao::all();
        foreach ($situacao_tipos as $obj_sit) {
            $items[$obj_sit->id] = $obj_sit->nome;
        }
        $this->combo_situacao->addItems($items);
        Transaction::close();       

        $row = new Row();

        $col = $row->addCol($this->combo_situacao);// add conteudo a coluna
        $col->class = 'col-6 col-sm-3';

        $data_finalizacao = new DateEntry('data_finalizacao_footer');
        $data_finalizacao->{'class'} = 'form-control';
        $col = $row->addCol($data_finalizacao);

        $finalizaNegociacao = $this->form->addAction('Finalizar Negociação', new Action(array($this, 'finalizaNegociacao')));
        $col = $row->addCol($finalizaNegociacao);// add conteudo a coluna
        $col->class = 'col';

        $this->panel->setFooter($row);

        parent::add($this->panel);

    }

    function management($param)
    {        
        if(isset($param['id'])) {            
            Transaction::open($this->connection);

            $id = $param['id'];
            $negociacao = new Negociacao($id);   

            $form_data = new stdClass;

            // dados do contrato
            $form_data->negociacao_id = $id;
            $contrato = $negociacao->getContrato();
            $form_data->nome_cliente    = $contrato->getCliente()->nome;
            $form_data->numero_contrato = $contrato->projeto . '-' . $contrato->numero;
            $form_data->nome_projeto    = $contrato->produto;
            $form_data->valor_venda     = number_format( $contrato->getValorVenda(), 2, ',', '.');
            $form_data->pontos_contrato = $contrato->pontos;
            $form_data->data_venda      = date('d/m/Y', strtotime($contrato->data_venda));
            $vVenda = $contrato->getValorVenda();
            if ($vVenda != 0) {
                $perc_int = round(($contrato->getValorPago() / $contrato->getValorVenda() * 100), 2)  . '%';
            } else {
                $perc_int = 'N/D';
            }
            $form_data->integralizacao  = $perc_int;

            //dados da ocorrencia
            $ocorrencia = $negociacao->getOcorrencia();
            $form_data->numero_ocorrencia = $ocorrencia->numero_ocorrencia;
            $form_data->data_ocorrencia   = date(CONF_DATE_BR, strtotime($ocorrencia->dtocorrencia));

            //dados Negociação
            $form_data->tipo_solicitacao  = $negociacao->get_tipo_solicitacao();
            $form_data->origem            = $negociacao->get_origem();
            $form_data->situacao          = $negociacao->get_situacao();

            $this->form->setData($form_data);

            Transaction::close();
        }
    }


    public static function finalizaNegociacao()
    {
        // $dados = $this->form->getData();
        // var_dump($dados);
        // var_dump($_POST);
        
        //$this->form->setData($this->form->getData());

        if(isset($_POST['situacao_id']) AND !empty($_POST['situacao_id'])){
            $dados = (object) $_POST;    
            
            if (isset($_POST['data_finalizacao']) AND !empty($_POST['data_finalizacao'])) {

                try {
                    Transaction::open('bp_renegociacao');
    
                    $negociacao_id = (int) $dados->negociacao_id;
                    $negociacao = new Negociacao($negociacao_id);
        
                    $situacao_id = (int) $dados->situacao_id;
                    switch ($situacao_id) {
                        case 2: //cancelado
                            $contrato = $negociacao->getContrato();
                            $contrato->cancelado = true;
                            $contrato->store();

                            $negociacao->data_finalizacao = $dados->data_finalizacao;
                            $negociacao->multa            = $dados->multa;
                            $negociacao->reembolso        = $dados->reembolso;
                            $negociacao->numero_pc        = $dados->numero_pc;
                            $negociacao->taxas_extras     = $dados->taxas_extras;
                            $negociacao->situacao_id      = $situacao_id;
                            $negociacao->finalizada       = true;
                            $negociacao->store();
                            
                            Transaction::close();
                            return 'Cancelamento registrado com sucesso!';
                            break;
                        
                        case 6:

                            Transaction::close();
                            return 'Retenção';
                            break;
        
                        case 7:

                            Transaction::close();
                            return 'Reversão';
                            break;
        
                        default:
                            
                            $situacao = new Situacao($situacao_id);
                            Transaction::close();
                            return 'Negociação finalizada como: ' . $situacao->nome;
                            break;
                    }
                    
                } catch (Exception $e) {
                    //Transaction::rollback();
                    return $e->getMessage();
                }

            } else {
                //$this->combo_situacao->setValue($dados->situacao_id);
                return "Informe a data de finalização!";
            }

        } else {
            return "Escolha uma opção válida para finalizar a negociação!";
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
            Transaction::rollback();
            new Message('warning', "<b>Erro:</b> " . $e->getMessage());
        }
    }

}