<?php
namespace Library\Widgets\Container;

use Library\Widgets\Base\Element;

class Card extends Element
{
    private $header;
    private $body;
    private $footer;
    private $card_title;

    public function __construct($card_title = '', $divider = null)
    {        
        parent::__construct('div');
        $this->class = 'card mb-3';

        //cria o cabeçalho do card
        $this->header = new Element('h5');
        $this->header->class = 'card-header';

        //cria o corpo do card
        $this->body = new Element('div');
        $this->body->class = 'card-body';

        //se for informado um titulo no construtor esse titulo fica dentro do body do card
        //if ($card_title)
        //{
            $this->card_title = new Element('h5');
            //$label = new Element('h5');
            $this->card_title->class = 'card-title';
            $this->card_title->add($card_title);
            $this->body->add($this->card_title);
            if ($divider) {
                $div = new Element('hr');
                $div->{'style'} = $divider;
                $this->body->add($div);
            }
        //}

        //cria o footer do card
        $this->footer = new Element('div');
        $this->footer->class = 'card-footer';

    }

    public function setHeader($header, $align = 'justify')
    {   
        $this->header->add($header);
        $this->header->align = $align;
        parent::add($this->header);
    }

    public function setBody($content)
    {
        $this->body->add($content);
        parent::add($this->body);
    }

    public function setFooter($footer)
    {
        $this->footer->add($footer);
        parent::add($this->footer);
    }

    public function setCardTitle($card_title)
    {
        $this->card_title->add($card_title);
    }

}