<?php
namespace Library\Widgets\Form;

use Library\Control\ActionInterface;

class Button extends Field implements FormElementInterface
{
    private $action;
    private $label;
    private $formName;
    private $submitForm;

    public function setAction(ActionInterface $action, $label, $submitForm = false)
    {
        $this->action = $action;
        $this->label = $label;
        $this->submitForm = $submitForm;
    }

    public function setFormName($name)
    {
       $this->formName = $name;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function getLabel()
    {
        $this->label;
    }

    public function show()
    {
        $url = $this->action->serialize();
        //define as propriedades do botão
        $this->tag->name = $this->name; //nome da tag
        $this->tag->type = 'button'; //tipo de input
        //$this->tag->class = $this->class;    
        $this->tag->value = $this->label; //rótulo do botão
        //define a ação do botão
        if ($this->submitForm) {
            $this->tag->onclick = "document.{$this->formName}.action='{$url}';"."document.{$this->formName}.submit()";
        } else {
            $this->tag->onclick = "document.{$this->formName}.action='{$url}';";
        }
        //exibe o botão
        $this->tag->show();
    }
}

