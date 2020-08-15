<?php

use Library\Control\Page;
use Library\Control\Action;
use Library\Widgets\Base\Element;
use Library\Widgets\Datagrid\Datagrid;
use Library\Widgets\Datagrid\DatagridColumn;
use Library\Widgets\Datagrid\DatagridAction;
use Library\Widgets\Datagrid\DatagridActionAjax;
use Library\Widgets\Dialog\Modal;
use Library\Widgets\Wrapper\DatagridWrapper;
use Library\Traits\DeleteTrait;
use Library\Traits\ReloadTraitTeste;
use Library\Traits\ConfirmTrait;

class UsersList extends Page
{
    private $loaded;
    private $filter;
    private $connection;
    private $activeRecord;
    private $total_registers = 0;
    
    use DeleteTrait;
    use ConfirmTrait;
    use ReloadTraitTeste{
        onReload as onReloadTrait;
    }

    public function __construct()
    {
        parent::__construct();

        $this->connection = 'bp_renegociacao';

        //ação para um novo usuário
        $newUser = new Action(array(new UsersForm, 'onEdit'));
        $btn = new Element('a');
        $btn->class = "btn btn-primary mb-3";
        $btn->href = $newUser->serialize();
        $btn->add('Novo Usuário');
        parent::add($btn);

        //instancia o obj Datagrid
        $datagrid = new DatagridWrapper(new Datagrid);

        //instancia as colunas da Datagrid - Cabeçalho
        $id    = new DatagridColumn('id', 'Id', 'center', 40);
        $email = new DatagridColumn('email', 'Email Usuário', 'center', 200);
        // $grupo = new DatagridColumn('nome_grupo', 'Grupo Permissões', 'center', 200);

        //adiciona as colunas à Datagrid
        $datagrid->addColumn($id);
        $datagrid->addColumn($email);
        // $datagrid->addColumn($grupo);

        //instancia duas ações da datagrid
        $action1 = new DatagridAction( [new UsersForm, 'onEdit'] );
        $action1->setLabel('Editar');
        $action1->setImage('ico_edit.png');
        $action1->setField('id');

        $actDelUser = new Action(array($this, 'Delete'));
        $linkDelUser = $actDelUser->serialize();
        $action2 = new DatagridActionAjax('confirm', $linkDelUser, 'Users');
        $action2->setLabel('Excluir');
        $action2->setImage('ico_delete.png');
        $action2->setField('id');

        $datagrid->addAction($action1);
        $datagrid->addAction($action2);

        //cria o modelo da Datagrid montando sua estrutura (cabeçalho)
        $datagrid->createModel();

        //adiciona a Datagrid a página
        parent::add($datagrid);

        //armazena o activeRecord como chave no array activeRecord 
        //e indexa ao mesmo, o datagrid criado
        $this->activeRecord['Users'] = $datagrid;

        // modal
        $modal = new Modal("Excluir Registro", "ModalConfirm");
        $modal->add('Tem certeza que deseja excluir o registro?');
        parent::add($modal);
    }

    public function onReload()
    {
        $this->onReloadTrait();
        $this->loaded = true;
    }

    public function converterParaMaiusculo($value)
    {
        return strtoupper($value);
    }

    function show()
    {
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }
}