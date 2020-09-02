<?php

use Library\Control\Page;
use Library\Widgets\Dialog\Message;

class HelpersTest extends Page
{
    public function show()
    {
        // string
        $string = "Esta é uma string, nela temos um under_score e um guarda-chuva!";
        echo str_slug($string) . '<br>';
        echo str_studly_case($string) . '<br>';
        echo str_camel_case($string) . '<br>';

        echo str_title($string) . '<br>';
        echo str_limit_words($string, 8) . '<br>';
        echo str_limit_chars($string, 48) . '<br>';

        // validate
        $senha = "emailemailemailemail";
        if (is_passwd($senha)) {
            echo "Senha Válido!";
        } else {
            echo "Senha Inválido!";
        }

        //navigation
        var_dump(
            url("/blog/titulo-do-artigo"),
            url("blog/titulo-do-artigo")
        );

        // currency

        $currency = "111.236,59";
        
        echo str_format_currency($currency);
        
    }
}