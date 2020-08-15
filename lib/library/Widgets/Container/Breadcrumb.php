<?php
namespace Library\Widgets\Container;

use Library\Control\Action;
use Library\Control\ActionInterface;
use Library\Widgets\Base\Element;

class Breadcrumb extends Element
{
    private $list;

    public function __construct()
    {
        parent::__construct('nav');
        $this->{'aria-label'} = "breadcrumb";
        
        $this->list = new Element('ol');
        $this->list->{'class'} = "breadcrumb";

        parent::add($this->list);
    }

    public function addBreadCrumbItem(string $label, Action $action = null)
    {
        $item = new Element('li');
        $item->{'class'} = "breadcrumb-item";
        if ($action) {
            $link = new Element('a');
            $url = $action->serialize();
            $link->{'href'} = $url;
            $link->add($label);
            $item->add($link);
        } else {
            $item->{'class'} = "breadcrumb-item active";
            $item->add($label);
        }

        $this->list->add($item);
    }
}