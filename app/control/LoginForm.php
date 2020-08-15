<?php

use Library\Control\Page;
use Library\Control\Action;
use Library\Widgets\Base\Element;
use Library\Widgets\Form\Form;
use Library\Widgets\Form\Entry;
use Library\Widgets\Form\Password;
use Library\Widgets\Wrapper\FormWrapper;
use Library\Widgets\Dialog\Message;
use Library\Database\Transaction;
use Library\Database\Repository;
use Library\Database\Criteria;
use Library\Database\Filter;
use Library\Session\Session;
use Library\Widgets\Base\Image;
use Library\Widgets\Container\Card;
use Library\Widgets\Form\Label;
use Library\Widgets\Wrapper\BootstrapFormBuilder;

class LoginForm extends Page
{
    /** @var Form */
    private $form;

    public function __construct()
    {

        parent::__construct();

        $div = new Element('div');
        $div->class = 'wrapper';
        
        // $logo = new Image('app/images/logo-vacation-club.jpeg');
        // $logo->{'class'} = 'mb-4';
        // $logo->{'width'} = '110';
        // $logo->{'heigth'} = '62';
        
        // $div->add($logo);

        // $this->form = new FormWrapper(new Form('form_login'), null, 'buttonLogin');
        // $this->form->setFormTitle('Renegociação TS');

        $email = new Entry('email');
        $email->id = 'email';

        $pass = new Password('password');
        $pass->id = 'password';

        // $this->form->addField('Email', $email);
        // $this->form->addField('Senha', $pass);

        // $this->form->addAction('Login', new Action(array($this, 'onLogin')));

        $this->form = new BootstrapFormBuilder('form_login');

        $this->form->addFields( [new Label('Email'), $email] );
        $this->form->addFields( [new Label('Senha'), $pass] );

        $bntLogin = $this->form->addAction('Login', new Action(array($this, 'onLogin')));
        $bntLogin->{'class'} = 'btn btn-info buttonLogin';
        $card = new Card();
        $card->setHeader('Renegociação TS', 'center');
        $card->setBody($this->form);
        $card->setFooter($bntLogin);

        $div->add($card);

        //$div->add($this->form);

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

                $user_array_data = $repository->load($criteria);

                if($user_array_data){

                    $user = new Users();
                    $user->fromArray($user_array_data[0]);

                    Session::setValue( 'logged', true );
                    Session::setValue( 'user', $user->toArray() );
                    
                    // var_dump($_SESSION,
                    //     Session::getValue('user')->nome,
                    //     Session::getValue('user')->ts_usuario_id
                    // );
                    // die;                    

                    // Session::setValue( 'user_email', $user[0]->email );
                    // Session::setValue( 'usuario_id', $user[0]->id );
                    // Session::setValue( 'ts_usuario_id', $user[0]->ts_usuario_id );

                    Transaction::close();
                    header("Location: index.php");

                } else {
                    Transaction::close();
                    new Message('danger', "Usuário não encontrado :(", '100', 'AlertLogin');
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
        Session::setValue('logged', false);
        Session::unSet('user');
        echo "<script language='JavaScript'> window.location = 'index.php'; </script>";
    }
}
