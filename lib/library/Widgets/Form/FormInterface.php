<?php
namespace Library\Widgets\Form;

interface FormInterface
{
    public function setName($name);
    public function getName();
    public function addField(FormElementInterface $field);
    //public function delField(FormElementInterface $field);
    public function setFields($fields);
    public function getField($name);
    public function getFields();
    //public function clear();
    public function setData($object);
    public function getData($class = 'StdClass');
    //public function validate();
    public function show();
}