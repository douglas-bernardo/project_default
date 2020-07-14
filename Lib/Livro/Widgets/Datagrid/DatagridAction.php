<?php
namespace Livro\Widgets\Datagrid;

use Livro\Control\Action;

class DatagridAction extends Action
{
    private $image;
    private $label;
    private $field;
    private $class;
    private $style;

    public function setImage($image)
    {
        $this->image = $image;
    }
    public function getImage()
    {
        return $this->image;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }
    public function getLabel()
    {
        return $this->label;
    }
    
    public function setField($field)
    {
        $this->field = $field;
    }
    public function getField()
    {
        return $this->field;
    }

    public function setClass($className)
    {
        $this->class = $className;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setStyle($style)
    {
        $this->style = $style;
    }

    public function getStyle()
    {
        return $this->style;
    }
    
}
