<?php
namespace Livro\Widgets\Wrapper;

use Livro\Control\ActionInterface;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Form\Button;
use Livro\Widgets\Form\Divider;
use Livro\Widgets\Form\FormBase;
use Livro\Widgets\Form\FormElementInterface;
use Livro\Widgets\Form\FormInterface;
use Livro\Widgets\Form\Label;

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
    private $layout = 'row';

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

    public function addField(FormElementInterface $field)
    {
        return $this->decorated->addField($field);
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

        // echo "<pre>";
        // var_dump($args);
        // echo "</pre>";
        // die;

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
                    $item->{'class'} = 'form-control';
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


    private function render()
    {

    }

    public function show()
    {
        $this->decorated->show();
    }

}
