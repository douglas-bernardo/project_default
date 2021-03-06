<?php
namespace Library\Widgets\Container;

use Library\Widgets\Base\Element;

class TableRow extends Element
{
    public function __construct(){
        parent::__construct('tr');
    }

    public function addCell($value){
        //instancia obj celula
        $cell = new TableCell($value);
        parent::add($cell);
        return $cell; //retorna o obj cell instanciado
    }   
}