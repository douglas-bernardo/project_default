<?php

use Library\Control\Action;
use Library\Control\Page;
use Library\Database\Filter;
use Library\Database\Transaction;
use Library\Log\LoggerTXT;
use Library\Session\Session;
use Library\Traits\ReloadTrait;
use Library\Widgets\Base\Element;
use Library\Widgets\Container\Breadcrumb;
use Library\Widgets\Container\Card;
use Library\Widgets\Container\Row;
use Library\Widgets\Datagrid\Datagrid;
use Library\Widgets\Datagrid\DatagridColumn;
use Library\Widgets\Datagrid\DatagridActionAjax;
use Library\Widgets\Datagrid\PageNavigation;
use Library\Widgets\Dialog\GenericModal;
use Library\Widgets\Dialog\Message;
use Library\Widgets\Form\Combo;
use Library\Widgets\Form\Hidden;
use Library\Widgets\Form\Label;
use Library\Widgets\Wrapper\BootstrapFormBuilder;
use Library\Widgets\Wrapper\DatagridWrapper;

class OcorrenciasList extends Page
{

    private $loaded;
    private $filter;   
    private $criteria;
    private $connection;
    private $activeRecord;
    private $breadcrumb;
    private $datagrid;
    private $order_param;
    protected $pageNavigation;
    private $form;
    private $total_ocorrencias;
    private $total_registers;

    /**
     * Undocumented variable
     *
     * @var LoggerTXT
     */
    private $logger;

    use ReloadTrait{
        onReload as onReloadTrait;
    }

    public function __construct() 
    {
        parent::__construct();

        $this->logger =  new LoggerTXT('tmp/ocorrencias_proccess.txt');

        $this->connection   = 'bp_renegociacao';
        $this->activeRecord = 'Ocorrencia';

        //breadcrumb
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->addBreadCrumbItem('Ocorrências');
        parent::add($this->breadcrumb);

        //instancia o obj Datagrid
        $this->datagrid = new DatagridWrapper( new Datagrid );

        //instancia as colunas da Datagrid - Cabeçalho 
        $id              = new DatagridColumn('id', 'id', 'center', '');     
        $numero          = new DatagridColumn('numero_ocorrencia', 'Número', 'center', '5%');
        $data_ocorrencia = new DatagridColumn('dtocorrencia', 'Data', 'center', '8%');
        $motivo          = new DatagridColumn('descricao', 'Motivo', 'justify', '21%');
        $cliente         = new DatagridColumn('nome_cliente', 'Cliente', 'justify', '26%');
        $projeto         = new DatagridColumn('numeroprojeto', 'Proj.', 'center', '4%');
        $contrato        = new DatagridColumn('numerocontrato', 'Contrato', 'center', '10%');
        $resp            = new DatagridColumn('nomeusuario_resp', 'Resp.', 'center', '10%');
        $status          = new DatagridColumn('status', 'Status TS', 'center', '20%');

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
        //$cliente->setTransformer(array($this, 'setFirstUpper'));
        $resp->setTransformer(array($this, 'setFirstUpper'));
        $status->setTransformer(array($this, 'setStatus'));
        $data_ocorrencia->setTransformer(array($this, 'formatDate'));

        // Datagrid Action Ajax 
        $action2 = new DatagridActionAjax('negociacao');
        $action2->setLabel('Registrar Negociação');
        $action2->setImage('support.png');
        $action2->setField('id');
        $this->datagrid->addAction($action2, '4%');

        //cria o modelo da Datagrid montando sua estrutura (cabeçalho)
        $this->datagrid->createModel();

        // create the page navigation
        $this->pageNavigation = new PageNavigation;
        $this->pageNavigation->setAction(new Action(array($this, 'onReload')));

        // insert datagrid on card
        $card = new Card();
        $row = new Row();
        $row->{'class'} = 'row justify-content-between';
        $title = $row->addCol('Ocorrências Timesharing');
        $title->{'class'} = 'col';
        $this->total_ocorrencias = $row->addCol();
        $this->total_ocorrencias->{'class'} = 'col';
        

        $card->setHeader($row);
        //$card->setHeader('Ocorrências Timesharing');
        $card->setBody($this->datagrid);
        $card->setFooter($this->pageNavigation);

        // insert card on page
        parent::add($card);

        // *********************** MODAL FORM *************************************

        //$this->form = new FormWrapperModal(new Form('form_negociacao_register'));
        $this->form = new BootstrapFormBuilder('form_negociacao_register');
        $this->form->setFormTitle('Dados da Negociação');

        // hidden fields
        $ocorrencia_id = new Hidden('ocorrencia_id');
        $ocorrencia_id->{'id'} = 'ocorrencia_id';
        $ocorrencia_id->setEditable(false);

        $origem           = new Combo('origem_id', 'combo', 'Selecione a origem...');
        $tipo_solicitacao = new Combo('tipo_solicitacao_id', 'combo', 'Selecione o tipo de solicitação...');  
        
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
        
        // $this->form->addField('id_ocorrencia', $ocorrencia_id);
        // $this->form->addField('Origem', $origem);
        // $this->form->addField('Tipo de Solicitação', $tipo_solicitacao);
        $this->form->addFields([$ocorrencia_id]);
        $this->form->addFields([new Label('Origem'), $origem]);
        $this->form->addFields([new Label('Tipo de Solicitação'), $tipo_solicitacao]);

        
        //$act = new Action(array($this, 'saveNegociacao'));
        //$this->form->addAction('Salvar', $act);

        $act = $this->form->addAction('Registrar Negociação', new Action( [$this, 'registraNegociacao'] ), true);   

        $modal = new GenericModal('ModalNegociacao');
        $modal->setHeader($this->form->getTitle());
        $modal->setBody($this->form);
        //$modal->setDefaultCloseOperation();
        $modal->setFooter($act);

        //parent::add($this->form);
        parent::add($modal);

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

    public function registraNegociacao()
    {
        try{

            Transaction::open($this->connection);
            //Transaction::setLogger(new LoggerTXT('tmp/save_negociacao.txt'));            
            $dados = $this->form->getData();

            //obtem a instancia do objeto Ocorrencia e altera o atributo atendida
            $ocorrencia = new Ocorrencia($dados->ocorrencia_id);
            $ocorrencia->atendida = true;
            $ocorrencia->store();

            //cria um novo objeto Cliente
            $cliente = new Cliente();
            $cliente->nome          = $ocorrencia->nome_cliente;
            $cliente->ts_cliente_id = $ocorrencia->idpessoa_cliente;
            $cliente->store();

            //cria um novo objeto Contrato
            // *** IMPORTANTE: Adicionar validação para contratos inseridos manualmente no processo de reversão.
            $contrato = new Contrato();
            $contrato->cliente_id          = $cliente->id;
            $contrato->projeto             = $ocorrencia->numeroprojeto;
            $contrato->numero              = $ocorrencia->numerocontrato;
            $contrato->produto             = $ocorrencia->nomeprojeto;
            $contrato->ts_idvendats        = $ocorrencia->idvendats;
            $contrato->ts_idvendaxcontrato = $ocorrencia->idvendaxcontrato;
            $contrato->origem_contrato_id  = 1; //API service.

            //API CM - dados adicionais do contrato:
            $api_contrato_service = $this->apiContratoServicesGetData($ocorrencia->idvendaxcontrato);            
            if ($api_contrato_service) {
                $contrato->data_venda = $api_contrato_service->DATAVENDA;
                $contrato->validade   = $api_contrato_service->VALIDADE;
                $contrato->pontos     = $api_contrato_service->NUMEROPONTOS;
                $contrato->revertido  = ($api_contrato_service->FLGREVERTIDO == 'S') ? true : false;
                $contrato->cancelado  = ($api_contrato_service->FLGCANCELADO == 'S') ? true : false;
            } 
            $contrato->store();

            //API CM - lançamentos financeiro
            $docs = $this->apiTSLancamentosServicesGetData($ocorrencia->idvendats);
            if ($docs) {
                foreach ($docs as $doc) {
                    $array = (array) $doc;
                    $pacela = new TSLancamentos();
                    $pacela->fromArray($array);
                    $pacela->idvendaxcontrato = $ocorrencia->idvendaxcontrato;
                    $pacela->store();
                    unset($pacela);
                }
            }

            //cria um novo objeto Negociacao
            $negociacao = new Negociacao();   
            $negociacao->fromArray((array) $dados); 
            $negociacao->usuario_id = Session::getValue('user')->id;
            $negociacao->situacao_id = 1;
            $negociacao->contrato_id = $contrato->id;
            $negociacao->store();

            Transaction::close();
            Session::setValue('neg_register_process', true);
            header("Location: ?class=OcorrenciasList");

        } catch(Exception $e) {
            Transaction::rollback();
            new Message('warning', "<b>Erro:</b> " . $e->getMessage());
        }        
    }
    
    private function apiTSLancamentosServicesGetData($idvendats)
    {
        // API CM - https://localhost/wser_cm/?class=TSLancamentosServices&method=getData&idvendats=54142
        $location = CONF_CM_SERVICE . 'resp.php';
        $parameters['class']  = 'TSLancamentosServices';
        $parameters['method'] = 'getData';
        $parameters['idvendats'] = $idvendats;
        $url = $location . '?' . http_build_query($parameters);
        $result = json_decode(file_get_contents($url));
        if ($result) {
            if ($result->status == 'success') {
                if (isset($result->data->exception) && $result->data->exception) {
                    $log  = $result->data->exception->class . PHP_EOL;
                    $log .= $result->data->exception->method . PHP_EOL;
                    $log .= $result->data->exception->data;
                    $this->logger->write($log);
                } else {
                    return $result->data;
                }
            }
        }
        $this->logger->write($log);
        return null;
    }

    private function apiContratoServicesGetData($idvendaxcontrato): ?object
    {
        // API CM - https://localhost/wser_cm/?class=ContratoServices&method=getData&idvendaxcontrato=128386
        $location = CONF_CM_SERVICE . 'resp.php';
        $parameters['class']  = 'ContratoServices';
        $parameters['method'] = 'getData';
        $parameters['idvendaxcontrato'] = $idvendaxcontrato;
        $url = $location . '?' . http_build_query($parameters);
        $result = json_decode(file_get_contents($url));
        if ($result) {
            if ($result->status == 'success') {                
                if (isset($result->data->exception) && $result->data->exception) {
                    $log  = $result->data->exception->class . PHP_EOL;
                    $log .= $result->data->exception->method . PHP_EOL;
                    $log .= $result->data->exception->data;
                    $this->logger->write($log);
                } else {
                    return $result->data;
                }
            }                
        }
        $this->logger->write($log);
        return null;
    }

    public function onReload()
    {
        if (Session::getValue('neg_register_process')) {
            $link = new Element('a');
            $link->{'class'} = 'alert-link';
            $link->{'href'} = '?class=NegociacaoList';
            $link->add('negociações');
            new Message('success', "Negociação registrada com sucesso! Clique em {$link}, para gerenciar suas ocorrências");
            Session::unSet('neg_register_process');
        }
        
        // order to show
        $this->order_param = 'numero_ocorrencia DESC';

        // filtro de ocorrências
        $this->filter[] = new Filter('idusuario_resp', '=', Session::getValue('user')->ts_usuario_id);
        $this->filter[] = new Filter('idmotivots', 'IN', array(364, 365, 427, 683, 702, 993));
        $this->filter[] = new Filter('atendida', '=', false);
        
        $param = $_REQUEST;
        $this->onReloadTrait($param);
    }

    function show()
    {        
        if(!$this->loaded){
            $this->onReload();
            $this->total_ocorrencias->add('<h6>Total: <span class="badge badge-warning">' . $this->total_registers . '</span> ocorrências</h3>');
            
        }        
        parent::show();        
    }
}