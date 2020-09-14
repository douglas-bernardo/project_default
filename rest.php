<?php

header('Content-type: application/json; charset=utf-8');

require __DIR__ . '/vendor/autoload.php';

class RestServer
{
    public static function run($request)
    {
        
        $class  = isset($request['class']) ? $request['class'] : '';
        $method = isset($request['method']) ? $request['method'] : '';
        $response = null;

        try {
            if (class_exists($class)) {
                if (method_exists($class, $method)) {
                    $response = call_user_func(array($class, $method), $request);
                    return json_encode(array('status'=>'success', 'data'=>$response));
                } else {
                    $error_msg = "Método {$class}::{$method} não encontrada";
                    return json_encode(array('status' => 'error', 'data' => $error_msg));
                }
                
            } else {
                $error_msg = "Classe {$class} não encontrada";
                return json_encode(array('status' => 'error', 'data' => $error_msg));
            }

        } catch (\Exception $e) {
            return json_encode(array('status' => 'error', 'data' => $e->getMessage()));
        }
    }
}

print RestServer::run($_REQUEST);