<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Password;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Dialog\Message;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Database\Filter;
use Livro\Session\Session;

class LoginForm extends Page
{
    /** @var Form */
    private $form;

    public function __construct()
    {

        parent::__construct();

        $div = new Element('div');
        $div->class = 'wrapper';

        $this->form = new FormWrapper(new Form('form_login'), null, 'buttonLogin');
        $this->form->setFormTitle('Renegociação TS');

        $email = new Entry('email');
        $email->id = 'email';

        $pass = new Password('password');
        $pass->id = 'password';

        $this->form->addField('Email', $email);
        $this->form->addField('Senha', $pass);

        $this->form->addAction('Login', new Action(array($this, 'onLogin')));

        $div->add($this->form);

        parent::add($div);

    }
    
    public function onLogin()
    {
        if(isset($_POST['email']) AND !empty($_POST['password'])){

            try {

                $email = $_POST['email'];
                $password = $_POST['password'];

                Transaction::open('bp_renegociacao');
                $repository = new Repository('Users');

                $criteria = new Criteria;   
                $criteria->add(new Filter('email', '=', $email));
                $criteria->add(new Filter('password', '=', md5($password)));

                $user = $repository->load($criteria);

                if($user){

                    Session::setValue( 'logged', true );
                    Session::setValue( 'user_email', $user[0]->email );
                    Session::setValue( 'ts_usuario_id', $user[0]->ts_usuario_id );

                    Transaction::close();
                    header("Location: index.php");
                } else {
                    new Message('danger', "Usuário não encontrado :(", '100', 'AlertLogin');
                    Transaction::close();
                }

            } catch (\Exception $e) {
                new Message('danger', $e->getMessage());
            }

        } else {
            new Message('warning ', "Informe seu usuário e senha...", '100', 'AlertLogin');
        }
    }

    public function onLogout()
    {
        Session::setValue('logged', FALSE);
        echo "<script language='JavaScript'> window.location = 'index.php'; </script>";
    }
}
