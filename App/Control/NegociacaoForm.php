<?php

use Livro\Control\Page;
use Livro\Widgets\Form\Form;
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
        $this->form = new FormWrapper(new Form('form_negociacao'));
        $this->form->setFormTitle('Dados da Negociação');
        

    }
}