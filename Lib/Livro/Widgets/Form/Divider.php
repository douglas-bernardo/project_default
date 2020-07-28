<?php
namespace Livro\Widgets\Form;

use Livro\Widgets\Base\Element;

class Divider extends Element
{
    private $label;
    private $divider;

    public function __construct(string $label = '', string $style = '') {
        parent::__construct('div');
        $this->label = new Element('h5');
        $this->label->add($label);
        $this->divider = new Element('hr');
        $this->divider->{'style'} = $style;
        parent::add($this->label);
        parent::add($this->divider);
    }
}