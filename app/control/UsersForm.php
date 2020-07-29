<?php

use Library\Control\Page;
use Library\Control\Action;
use Library\Widgets\Form\Form;
use Library\Widgets\Form\Entry;
use Library\Widgets\Form\Hidden;
use Library\Widgets\Form\Combo;
use Library\Widgets\Wrapper\FormWrapper;

use Livro\Traits\SaveTrait;
use Livro\Traits\EditTrait;

class UsersForm extends Page
{
    private $form;
    private $connection;
    private $activeRecord;
    private $url_save_return;

    use SaveTrait;
    use EditTrait;

    public function __construct() {
        parent::__construct();

        $this->connection   = 'bp_renegociacao';
        $this->activeRecord = 'Users';
        $this->url_save_return = 'index.php?class=UsersList&method=confirm&type=salvo&activeRecord=UsersList';
        
        //instancia de um formulário
        $this->form = new FormWrapper(new Form('form_users'), 'row');
        $this->form->setFormTitle('Cadastro de Usuários');

        //cria os campos do formulário
        $id               = new Hidden('id');
        $user_email       = new Entry('email');
        $user_email->id   = 'email';
        $user_pass        = new Entry('password');
        $user_pass->id    = 'password';
        $permission_group = new Combo('id_group');
        $company          = new Combo('id_company');

        $this->form->addField('Id', $id, '10%');
        $this->form->addField('Email', $user_email);
        $this->form->addField('Senha', $user_pass );
        $this->form->addField('Grupo de Permissões', $permission_group);
        $this->form->addField('Compania', $company);

        $id->setEditable(FALSE);

       
        $this->form->addAction('Limpar', new Action(array($this, 'onClear')));

        parent::add($this->form);
        
    }

    public function onClear()
    {
        
    }
}
