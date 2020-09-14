<?php

require __DIR__ . '/vendor/autoload.php';

use Library\Session\Session;
new Session;

$content = '';
if (Session::getValue('logged')){
    $template = file_get_contents('app/templates/template.html');
    $class = 'HomeControl';
} else {    
    $template = file_get_contents('app/templates/login.html');
    $class = 'LoginForm';
}

if (isset($_GET['class']) AND Session::getValue('logged')){
    $class = $_GET['class'];
}

if(class_exists($class)){
    try{
        $pagina = new $class;         //Instancia a classe
        ob_start();                   //Inicia controle de output / buffer
        $pagina->show();              //Exibe a página
        $content = ob_get_contents(); //Lê o conteúdo gerado
        ob_end_clean();               //Finaliza controle de output // descarta o conteúdo do buffer. 
    }
    catch(Exception $e){
        $content = $e->getMessage() . '<br>' . $e->getTraceAsString();
    }
}

$email = '';
if (Session::getValue('user')) {
    $email = Session::getValue('user')->email;
}

$output = str_replace('{content}', $content, $template);
$output = str_replace('{class}', $class, $output);
$output = str_replace('{user_email}', $email, $output);

echo $output;