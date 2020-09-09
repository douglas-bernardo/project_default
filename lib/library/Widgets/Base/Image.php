<?php
namespace Library\Widgets\Base;

class Image extends Element
{
    public function __construct($source){
        parent::__construct('img');
        $this->src = $source;
    }
}