<?php
namespace Library\Control;

use Library\Session\Session;
use Library\Widgets\Base\Element;

class Page extends Element
{

    public function __construct()
    {
        parent::__construct('div');
    }
    //todas as classes filhas de page (classes control) herdarão e podem executar o metodo show()
    //override show
    public function show()
    {
        //nas classes onde existirem uma requisição GET segue o processo abaixo:
        //existe uma requisição GET?
        if ($_GET) {

            $class  = isset($_GET['class'])  ? $_GET['class'] : NULL;//existe uma classe definida?
            $method = isset($_GET['method']) ? $_GET['method'] : NULL;//existe um método?

            if (!Session::getValue('logged') && $class != 'LoginForm') {
                echo "<script language='JavaScript'> window.location = 'index.php'; </script>";
                return;
            }

            if ($class) {
                //a classe da URL é mesma da classe atual? (filha de PAGE)
                // se sim, realiza a instancia da mesma
                $object = $class == get_class($this) ? $this : new $class;
                // na classe atual existe o mmétodo?
                if ( method_exists($object, $method) ) {
                    call_user_func(array($object, $method), $_GET);
                }
            }
        }
        //nas classes onde não existe a requisição GET:
        parent::show();//executa o metodo show da classe pai Element
    }
}