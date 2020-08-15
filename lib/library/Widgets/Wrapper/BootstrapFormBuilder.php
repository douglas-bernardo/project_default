<?php
namespace Library\Widgets\Wrapper;

use Library\Control\ActionInterface;
use Library\Widgets\Base\Element;
use Library\Widgets\Form\Button;
use Library\Widgets\Form\Divider;
use Library\Widgets\Form\FormBase;
use Library\Widgets\Form\FormElementInterface;
use Library\Widgets\Form\FormInterface;
use Library\Widgets\Form\Label;

/**
 * Bootstrap form builder - Design Pattern Decorator
 * @author Jackson Douglas <jkdouglas21@gmail.com>
 * 
 */
class BootstrapFormBuilder implements FormInterface
{
    /**
     * Undocumented variable
     *
     * @var FormBase
     */
    private $decorated;

    public function __construct($name = 'my_form') {
        $this->decorated = new FormBase($name);
    }

    public function setName($name)
    {
        $this->decorated->setName($name);
    }

    public function getName()
    {
        $this->decorated->getName();
    }

    public function setFormTitle($title)
    {
        $this->decorated->setFormTitle($title);
    }

    public function getTitle(): ? string
    {
        return $this->decorated->getTitle();
    }

    public function addField(FormElementInterface $field)
    {
        $this->decorated->addField($field);
    }

    public function setFields($fields)
    {
        $this->decorated->setFields($fields);
    }

    public function getField($name)
    {
        return $this->decorated->getField($name);
    }

    public function getFields()
    {
        return $this->decorated->getFields();
    }

    public function setData($object)
    {
        return $this->decorated->setData($object);
    }

    public function getData($class = 'StdClass')
    {
        return $this->decorated->getData($class);
    }

    public function addAction($label, ActionInterface $action)
    {
        $name = \strtolower(str_replace(' ', '_', $label));
        $btn = new Button($name);
        $btn->setFormName($this->decorated->getName());
        $btn->setAction($action, $label);        
        $btn->{'class'} = 'btn btn-info';
        $this->decorated->addAction($label, $action);
        return $btn;
    }

    public function addFields()
    {

        $args = func_get_args();

        $form_row = new Element('div');
        $form_row->{'class'} = 'form-row';

        foreach ($args as $slot) {
            $size = '';
            if (isset($slot['size'])) {
                $size = $slot['size'];
                unset($slot['size']);
            }
            //$size = (isset($slot['size'])) ? $slot['size'] : '' ;
            $form_group = new Element('div');
            $form_group->{'class'} = 'form-group ' . $size;
            $form_row->add($form_group);
            foreach($slot as $item) {
                if (!$item instanceof Label) {
                    $control_sizing = ($item->getOptions('sizing')) ? $item->getOptions('sizing') : '' ;
                    $item->{'class'} = 'form-control ' . $control_sizing;
                }
                $form_group->add($item);
                $this->decorated->addField($item);                
            }            
        }

        if ($size != '') {
            $this->decorated->addElement($form_row); 
        } else {
            $this->decorated->addElement($form_group); 
        }
        
    }

    public function setDivider(Divider $divider)
    {
        $this->decorated->addElement($divider);
    }

    public function show()
    {
        $this->decorated->show();
    }

}
