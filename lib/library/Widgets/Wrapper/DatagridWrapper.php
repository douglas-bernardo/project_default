<?php
namespace Library\Widgets\Wrapper;

use Library\Widgets\Datagrid\Datagrid;
use Library\Widgets\Base\Element;

class DatagridWrapper extends Element
{
    private $decorated;

    public function __construct(Datagrid $datagrid)
    {
        parent::__construct('div');

        $responsive_wrapper = new Element('div');
        $responsive_wrapper->{'class'} = 'table-responsive';

        $this->decorated = $datagrid;
        $this->decorated->{'class'} = 'table table-sm table-hover';
        $this->decorated->{'style'} = 'font-size:12px';

        $responsive_wrapper->add($this->decorated);
        parent::add($responsive_wrapper);        
    }

    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->decorated, $method), $parameters);
    }

    public function __set($attribute, $value)
    {
        $this->decorated->$attribute = $value;
    }
}