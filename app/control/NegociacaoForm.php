<?php

use Library\Control\Action;
use Library\Control\Page;
use Library\Database\Criteria;
use Library\Database\Filter;
use Library\Database\Repository;
use Library\Database\Transaction;
use Library\Session\Session;
use Library\Widgets\Base\Script;
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
        $this->form->setClass('needs validation');
        $this->form->setMetaData('novalidate');
        
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
        $this->combo_situacao = new Combo(
            'situacao_id', 'form-control', 'Selecione para finalizar...');        
        Transaction::open('bp_renegociacao');
        $situacao_tipos = Situacao::all();
        if ($situacao_tipos) {
            foreach ($situacao_tipos as $obj_sit) {
                $items[$obj_sit->id] = $obj_sit->nome;
            }
            $this->combo_situacao->addItems($items);
        }
        Transaction::close();       

        $row = new Row();

        $col = $row->addCol($this->combo_situacao);// add conteudo a coluna
        $col->class = 'col-md-4 col-sm-4 mb-2';

        $data_finalizacao = new DateEntry('data_finalizacao_footer');
        $data_finalizacao->{'class'} = 'form-control';
        $data_finalizacao->setMetaData('required');
        $col = $row->addCol($data_finalizacao);
        $col->class = 'col-md-3 col-sm-4 mb-2';

        $finalizaNegociacao = $this->form->addAction('Finalizar Negociação', new Action(array($this, 'finalizaNegociacao')));
        $finalizaNegociacao->{'id'} = 'finaliza_negociacao';
        $col = $row->addCol($finalizaNegociacao);// add conteudo a coluna
        $col->class = 'col-md-4 col-sm-4 mb-2';

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
            $vl_venda = $contrato->getValorTotalLancamentos();
            $vl_venda = ($vl_venda == 0) ? $negociacao->getOcorrencia()->valor_venda : $vl_venda;
            $form_data->valor_venda     = number_format( $vl_venda, 2, ',', '.');
            $form_data->pontos_contrato = $contrato->pontos;
            $form_data->data_venda      = date('d/m/Y', strtotime($contrato->data_venda));
            $vVenda = $contrato->getValorTotalLancamentos();
            if ($vVenda != 0) {
                $perc_int = round(($contrato->getValorPago() / $contrato->getValorTotalLancamentos() * 100), 2)  . '%';
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
        usleep(400000);
        if(isset($_POST['situacao_id']) AND !empty($_POST['situacao_id'])) {
            if (isset($_POST['data_finalizacao']) AND !empty($_POST['data_finalizacao'])) {
                try {
                    Transaction::open('bp_renegociacao');

                    $dados = (object) $_POST;
                    $negociacao_id = (int) $dados->negociacao_id;
                    $negociacao = new Negociacao($negociacao_id);
        
                    $situacao_id = (int) $dados->situacao_id;
                    switch ($situacao_id) {

                        case 1: // Aguardando Retorno
                            Transaction::close();
                            return 'Negociação permanece como "Aguardando Retorno"!';
                            break;

                        case 2: // Cancelado
                            // obtem e atualiza contrato
                            $contrato = $negociacao->getContrato();
                            $contrato->cancelado = true;
                            $contrato->store();

                            $negociacao->data_finalizacao = $dados->data_finalizacao;
                            $negociacao->reembolso        = ($dados->reembolso) ?  str_format_currency( $dados->reembolso ) : '0.00';
                            $negociacao->taxas_extras     = ($dados->taxas_multas_extras) ? str_format_currency( $dados->taxas_multas_extras ) : '0.00';
                            $negociacao->numero_pc        = $dados->numero_pc;
                            $negociacao->situacao_id      = $situacao_id;
                            $negociacao->finalizada       = true;
                            $negociacao->store();
                            
                            Transaction::close();
                            return 'Cancelamento do contrato <b>' . $contrato->projeto . '-' . $contrato->numero . '</b> registrado com sucesso!';
                            break;
                        
                        case 6: // Retenção
                            // Obtem contrato
                            $contrato = $negociacao->getContrato();

                            // instancia de um novo objeto Retencao
                            $retencao = new Retencao();
                            $retencao->negociacao_id = $negociacao->id;
                            $retencao->contrato_id   = $contrato->id;
                            $retencao->valor_antigo  = $contrato->getValorTotalLancamentos(); // verificar em casos de API off, se é viável obter valor de venda antigo da tbl de ocorrencias
                            $retencao->data          = $dados->data_retencao;
                            $retencao->valor_novo    = str_format_currency( $dados->valor_financiado );
                            $retencao->store();

                            // atualiza negociação
                            $negociacao->data_finalizacao       = $dados->data_finalizacao;
                            $negociacao->valor_primeira_parcela = ($dados->valor_primeira_parcela) ? str_format_currency($dados->valor_primeira_parcela) : '0.00';
                            $negociacao->situacao_id            = $situacao_id;
                            $negociacao->finalizada             = true;
                            $negociacao->store();

                            // atualiza contrato
                            $contrato->valor_venda = str_format_currency($dados->valor_financiado);
                            $contrato->store();

                            Transaction::close();
                            return 'Retenção do contrato <b>' . $contrato->projeto . '-' . $contrato->numero . '</b> registrada com sucesso!';
                            break;
        
                        case 7: //Reversão
                            // Obtem contrato
                            $contrato_antigo = $negociacao->getContrato();
                            $contrato_antigo->revertido = true;
                            $contrato_antigo->store();

                            // novo contrato (reversão)
                            $contrato_novo = new Contrato();
                            $contrato_novo->cliente_id         = $contrato_antigo->getCliente()->id;
                            $contrato_novo->projeto            = $dados->rev_projeto;
                            $contrato_novo->numero             = $dados->rev_numero_contrato;
                            $contrato_novo->valor_venda        = str_format_currency($dados->rev_valor_venda);
                            $contrato_novo->origem_contrato_id = 2; // Reversão
                            $contrato_novo->store();
                            
                            // instancia de um novo objeto Retencao
                            $reversao = new Reversao();
                            $reversao->data            = $dados->data_reversao;
                            $reversao->negociacao_id   = $negociacao->id;
                            $reversao->novocontrato_id = $contrato_novo->id;
                            $reversao->store();

                            // atualiza negociação
                            $negociacao->data_finalizacao       = $dados->data_finalizacao;
                            $negociacao->reembolso              = ($dados->reembolso) ? str_format_currency($dados->reembolso) : '0.00';
                            $negociacao->taxas_extras           = ($dados->taxas_multas_extras) ? str_format_currency($dados->taxas_multas_extras) : '0.00';
                            $negociacao->valor_primeira_parcela = ($dados->valor_primeira_parcela) ? str_format_currency($dados->valor_primeira_parcela) : '0.00';
                            $negociacao->numero_pc              = $dados->numero_pc;
                            $negociacao->situacao_id            = $situacao_id;
                            $negociacao->finalizada             = true;
                            $negociacao->store();

                            Transaction::close();
                            $resp = 'Reversão do contrato <b>' . $contrato_antigo->projeto . '-' . $contrato_antigo->numero . '</b> registrada com sucesso! ';
                            $resp .=  'Novo contrato: ' . $contrato_novo->projeto . '-' . $contrato_novo->numero; 
                            return $resp;
                            break;
        
                        default:
                            $negociacao->data_finalizacao = $dados->data_finalizacao;
                            $negociacao->situacao_id      = $situacao_id;
                            $negociacao->finalizada       = true;
                            $negociacao->store();
                            $situacao = new Situacao($situacao_id);

                            Transaction::close();
                            return 'Negociação finalizada como: ' . $situacao->nome;
                            break;
                    }
                    
                } catch (Exception $e) {
                    Transaction::rollback();
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

    public static function getProjetos(): ? array
    {
        $items = [];
        Transaction::open('bp_renegociacao');
        //$projetos = Projeto::all();

        $repository = new Repository('Projeto');
        $criteria = new Criteria();
        $criteria->add(new Filter('flgativo', '=', 'S'));
        $criteria->setProperty('order', 'numeroprojeto');
        $projetos = $repository->load($criteria);   

        if ($projetos) {
            foreach ($projetos as $projeto) {
                $items[] = [
                    "id" => $projeto->id, 
                    "projeto" => $projeto->numeroprojeto
                ];
            }
        }
        Transaction::close(); 
        return $items;
    }

    public function saveNegociacao()
    {
        try{
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