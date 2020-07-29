<?php
namespace Library\Widgets\Container;

use Library\Widgets\Base\Element;

class TableCell extends Element
{
    public function __construct($value)
    {
        parent::__construct('td');
        parent::add($value);
    }
}
