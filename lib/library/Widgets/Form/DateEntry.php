<?php
namespace Library\Widgets\Form;

use Library\Widgets\Base\Element;

class DateEntry extends Field implements FormElementInterface
{
    public function __construct($name)
    {
        parent::__construct($name);   
        $this->tag->name = $this->name;
        $this->tag->value = $this->value;
        $this->tag->type = 'date';
    }

    public function setMetaData(string $data)
    {
        $this->tag->setMetaData($data);
    }


    public function show()
    {
        $this->tag->show();
    }
}
