<?php
namespace Library\Widgets\Form;

use Library\Widgets\Base\Element;

abstract class Field implements FormElementInterface 
{
    protected $name;
    protected $size;
    protected $value;
    protected $editable;
    protected $tag;
    protected $formLabel;
    protected $options;

    public function __construct($name){
        self::setEditable(true);
        self::setName($name);
        $this->tag = new Element('input');
    }

    public function setProperty($name, $value){
        $this->tag->$name = $value;
    }

    public function getProperty($name){
        return $this->tag->$name;
    }

    public function __set($name, $value){
        if(is_scalar($value)){
            $this->setProperty($name, $value);
        }
    }

    public function __get($name){
        return $this->getProperty($name);
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }

    public function setLabel($label){
        $this->formLabel = $label;
    }

    public function getLabel(){
        return $this->formLabel;
    }

    public function setValue($value){
        $this->value = $value;
    }

    public function getValue(){
        $this->value;
    }

    public function setEditable($editable){
        $this->editable = $editable;
    }

    public function getEditable(){
        return $this->editable;
    }

    public function setSize($width, $height = null){
        $this->size = $width;
    }

    public function setOptions($param, $value)
    {
        $this->options[$param] = $value;
    }

    public function getOptions($param)
    {
        if (isset($this->options[$param])) {
            return $this->options[$param];
        }
    }
}