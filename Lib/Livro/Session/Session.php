<?php
namespace Livro\Session;

class Session
{
    public function __construct() 
    {
        if(!session_id()){
            session_save_path(CONF_SES_PATH);
            session_start();
        }
    }


    public static function setValue($var, $value)
    {
        $_SESSION[$var] = (is_array($value)? (object) $value : $value);
    }

    public static function getValue($var)
    {
        if(isset($_SESSION[$var])){
            return $_SESSION[$var];
        }
    }

    public static function unSet(string $key)
    {
        unset($_SESSION[$key]);
    }

    public static function has($key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function regenerate()
    {
        session_regenerate_id(true);
    }

    public static function freeSession()
    {
        $_SESSION = array();
        session_destroy();
    }
}