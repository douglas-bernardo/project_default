<?php
namespace Library\Widgets\Container;

use Library\Widgets\Base\Element;

class Col extends Element
{
    public function __construct($content) {
        parent::__construct('div');
        parent::add($content);
    }
}