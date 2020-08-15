<?php
namespace Library\Widgets\Form;

use Library\Widgets\Base\Element;
use Library\Control\ActionInterface;

class FormBase implements FormInterface
{
    protected $element;
    protected $fields;
    protected $actions;
    protected $title;

    public function __construct($name = 'my_form')
    {
        $this->element = new Element('form');
        $this->element->{'enctype'} = "multipart/form-data";
        $this->element->{'method'} = 'post';
        $this->setName($name);
    }

    public function setName($name)
    {
        $this->element->{'name'} = $name;
    }

    public function getName()
    {
        return $this->element->{'name'};
    }

    public function setFormTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function addField(FormElementInterface $field)
    {
        $name = $field->getName();
        if (isset($name)) {
            $this->fields[$name] = $field;
        }
        return $field;
    }

    public function addElement($element)
    {
        $this->element->add($element);
    }

    public function addAction($label, ActionInterface $action)
    {
        $this->actions[$label] = $action;
    }

    public function setFields($fields)
    {
        if (is_array($fields)) {
            $this->fields = array();
            foreach ($fields as $field) {
                $this->addField($field);
            }
        } else {
            throw new \Exception('Method must receive a parameter of ' . __METHOD__ . ' Array');            
        }
    }

    public function getField($name)
    {
        if (isset($this->fields[$name]))
        {
            return $this->fields[$name];
        }
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function setData($object)
    {
        foreach ($this->fields as $name => $field){
            if ($name AND isset($object->$name)){
                if ($name != 'password'){ 
                $field->setValue($object->$name);
                }
            }
        }
    }

    public function getData($class = 'stdClass')
    {
        $object = new $class;

        foreach($this->fields as $key => $fieldObject){
            $val = isset($_POST[$key])? $_POST[$key] : '';
            if(!$fieldObject instanceof Button){
                $object->$key = $val;
            }
        }
        foreach ($_FILES as $key => $content) {
            $object->$key = $content['tmp_name'];
        }
        return $object;
    }

    public function show()
    {
        $this->element->show();
    }
}