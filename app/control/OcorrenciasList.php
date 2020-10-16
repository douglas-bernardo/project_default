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
    private $pageNavigation;
    private $form;
    private $total_ocorrencias;
    private $total_registers;

    /** @var LoggerTXT */
    private static $logger;

    use ReloadTrait{
        onReload as onReloadTrait;
    }

    public function __construct() 
    {
        parent::__construct();

        self::$logger =  new LoggerTXT('tmp/ocorrencias_proccess.txt');

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
        $motivo          = new DatagridColumn('motivo', 'Motivo', 'justify', '21%');
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
        $resp->setTransformer(array($this, 'setFirstUpper'));
        $status->setTransformer(array($this, 'setStatus'));
        $data_ocorrencia->setTransformer(array($this, 'formatDate'));

        // Datagrid Action Ajax 
        $action2 = new DatagridActionAjax('modal_reg_negociacao_open');
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

        // *********************** MODAL FORM *****************************
        $this->form = new BootstrapFormBuilder('form_negociacao_register');
        $this->form->setFormTitle('Dados da Ocorrência');

        // hidden fields
        $ocorrencia_id = new Hidden('ocorrencia_id');
        $ocorrencia_id->{'id'} = 'ocorrencia_id';
        $ocorrencia_id->setEditable(false);
        $idusuario_resp = new Hidden('idusuario_resp');
        $idusuario_resp->{'id'} = 'idusuario_resp';
        $idusuario_resp->setEditable(false);

        $origem           = new Combo('origem_id', 'combo', 
                                      'Selecione a origem...');
        $tipo_solicitacao = new Combo('tipo_solicitacao_id', 
                                      'combo', 
                                      'Selecione o tipo de solicitação...');  
        
        Transaction::open('bp_renegociacao');
        // origem
        $origens = Origem::all();
        $items = array();
        foreach ($origens as $obj_origem) {
            $items[$obj_origem->id] = $obj_origem->nome;
        }
        $origem->addItems($items);

        // tipo_solicitacao
        $tipo_sol = TipoSolicitacao::all();
        $items = array();
        foreach ($tipo_sol as $obj_tp) {
            $items[$obj_tp->id] = $obj_tp->nome;
        }
        $tipo_solicitacao->addItems($items);

        Transaction::close();
        
        $this->form->addFields([$ocorrencia_id]);
        $this->form->addFields([$idusuario_resp]);
        $this->form->addFields([new Label('Origem'), $origem]);
        $this->form->addFields(
            [new Label('Tipo de Solicitação'), 
            $tipo_solicitacao]
        );

        $act = $this->form->addAction(
            'Registrar Negociação', 
            new Action( [$this, 'registraNegociacao'] )
        );

        $act->{'id'} = 'registrar_negociacao';

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
        $status = array(
            'P' => 'Pendente', 
            'F' => 'Finalizado', 
            'C' => 'Cancelado'
        );
        return $status[$value];
    }    

    public function formatDate($value)
    {
        return date('d-m-Y', strtotime( $value ));
    }

    /**
     * Registra uma nova negociação apartir da lista de ocorrências TS 
     *
     * @return void
     */
    public static function registraNegociacao()
    {
        try{
            Transaction::open('bp_renegociacao');
            $dados = (object) $_POST;
            $user_resp = (new Users())->loadBy('ts_usuario_id', $dados->idusuario_resp);

            //obtem a instancia do objeto Ocorrencia e altera o atributo atendida
            $ocorrencia = new Ocorrencia($dados->ocorrencia_id);
            $ocorrencia->atendida = true;
            $ocorrencia->store();

            //cria um novo objeto Cliente
            $cliente = new Cliente();
            $cliente->nome          = $ocorrencia->nome_cliente;
            $cliente->ts_cliente_id = $ocorrencia->idpessoa_cliente;
            $cliente->store();    

            //instancia de um projeto
            $projeto = (new Projeto())->loadBy("idprojetots", $ocorrencia->idprojetots);
            
            // ******************   IMPORTANTE!!!   ***************************
            // Adicionar um método de validação para contratos inseridos 
            // manualmente no processo de reversão.
            // Um contrato já adicionado no processo de reversão, não pode ser 
            // adicionado novamente no processo de registro de negociação. 
            // O mesmo vale para retenções | se o contrato já foi retido uma vez, 
            // não será necessário adicioná-lo novamente na base do sistema.
            // cria um novo objeto Contrato
            
            $contrato = new Contrato();
            $contrato->cliente_id          = $cliente->id;
            $contrato->projeto_id          = $projeto->id;
            $contrato->numero              = $ocorrencia->numerocontrato;
            $contrato->valor_venda         = $ocorrencia->valor_venda;
            $contrato->ts_idvendats        = $ocorrencia->idvendats;
            $contrato->ts_idvendaxcontrato = $ocorrencia->idvendaxcontrato;
            $contrato->origem_contrato_id  = 1; //API service.

            //API CM - Obtém os dados adicionais do contrato no cm timesharing
            $api_contrato = self::apiContratoServicesGetData($ocorrencia->idvendaxcontrato);            
            if ($api_contrato) {
                $contrato->data_venda = $api_contrato->DATAVENDA;
                $contrato->validade   = $api_contrato->VALIDADE;
                $contrato->pontos     = $api_contrato->NUMEROPONTOS;
                $contrato->revertido  = ($api_contrato->FLGREVERTIDO == 'S') ? true : false;
                $contrato->cancelado  = ($api_contrato->FLGCANCELADO == 'S') ? true : false;
            } 
            $contrato->store();

            //API CM - lançamentos financeiro
            $docs = self::apiTSLancamentosServicesGetData($ocorrencia->idvendats);
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
            $negociacao->origem_id  = $dados->origem_id;
            $negociacao->tipo_solicitacao_id = $dados->tipo_solicitacao_id;
            $negociacao->ocorrencia_id = $dados->ocorrencia_id;
            $negociacao->usuario_id = $user_resp->id;
            $negociacao->situacao_id = 1;
            $negociacao->contrato_id = $contrato->id;
            $negociacao->store();

            Transaction::close();
            //Session::setValue('neg_register_process', true);            
            //return 'Negociação registrada com sucesso';
            // header('Content-type: application/json; charset=utf-8');
            // echo json_encode(array('status'=>'success', 'data'=>'Negociação registrada com sucesso'));
            //header("Location: ?class=OcorrenciasList");
            $link = new Element('a');
            $link->{'class'} = 'alert-link';
            $link->{'href'} = '?class=NegociacaoList';
            $link->add('aqui');

            echo json_encode([
                'status'=>'success', 
                'data'=>'Negociação registrada para a ocorrência nº: ' . $ocorrencia->numero_ocorrencia . ". Clique {$link}, para gerenciar suas ocorrências",
                'session'=>Session::getValue('user')->toArray()
            ]);            
            exit;

        } catch(Exception $e) {
            Transaction::rollback();
            new Message('warning', "<b>Erro:</b> " . $e->getMessage());
        }        
    }
    
    private static function apiTSLancamentosServicesGetData($idvendats)
    {
        // API CM - https://localhost/wser_cm/?class=TSLancamentosServices&method=getData&idvendats=54142
       // $location = CONF_URL_CM_SERVICE;
        $parameters['class']  = 'TSLancamentosServices';
        $parameters['method'] = 'getData';
        $parameters['idvendats'] = $idvendats;
        $url = CONF_URL_CM_SERVICE . '?' . http_build_query($parameters);
        $result = json_decode(file_get_contents($url));
        if ($result) {
            if ($result->status == 'success') {
                if (isset($result->data->exception) && $result->data->exception) {
                    $log  = 'Erro no processo de consulta CM - Descrição:' . PHP_EOL; 
                    $log .= '[local class=>' . __CLASS__ . '| local method=>' . __METHOD__ . ']' . PHP_EOL;
                    $log .= 'Service Error:' . PHP_EOL;
                    $log .= 'class: ' . $result->data->exception->class . PHP_EOL;
                    $log .= 'method: ' . $result->data->exception->method . PHP_EOL;
                    $log .= 'description: ' . $result->data->exception->data . PHP_EOL;
                    //self::$logger->write($log);
                } else {
                    return $result->data;
                }
            }
        }
        //self::$logger->write($log);
        return null;
    }

    private static function apiContratoServicesGetData($idvendaxcontrato): ?object
    {
        // API CM - https://localhost/wser_cm/?class=ContratoServices&method=getData&idvendaxcontrato=128386
        //$location = CONF_URL_CM_SERVICE . 'resp.php';
        $parameters['class']  = 'ContratoServices';
        $parameters['method'] = 'getData';
        $parameters['idvendaxcontrato'] = $idvendaxcontrato;

        $url = CONF_URL_CM_SERVICE . '?' . http_build_query($parameters);
        $result = json_decode(file_get_contents($url));

        if ($result) {
            if ($result->status == 'success') {                
                if (isset($result->data->exception)) {
                    $log  = 'Erro no processo de consulta CM - Descrição:' . PHP_EOL; 
                    $log .= '[local class=>' . __CLASS__ . '| local method=>' . __METHOD__ . ']' . PHP_EOL;
                    $log .= 'Service Error:' . PHP_EOL;
                    $log .= 'class: ' . $result->data->exception->class . PHP_EOL;
                    $log .= 'method: ' . $result->data->exception->method . PHP_EOL;
                    $log .= 'description: ' . $result->data->exception->data . PHP_EOL;
                    //self::$logger->write($log);
                } else {
                    return $result->data;
                }
            }                
        }
        //self::$logger->write($log);
        return null;
    }

    public function onReload()
    {
        if (isset($_GET['success']) && $_GET['success']==true) {
            $link = new Element('a');
            $link->{'class'} = 'alert-link';
            $link->{'href'} = '?class=NegociacaoList';
            $link->add('negociações');
            new Message(
                'success', 
                "Negociação registrada com sucesso! Clique em {$link}, para gerenciar suas ocorrências");
        }

        // order to show
        $this->order_param = 'numero_ocorrencia DESC';
        // filtro de ocorrências Session::getValue('user')->ts_usuario_id
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