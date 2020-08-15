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
        // $this->header = new Element('div');
        // $this->header->class = 'card-header';
        // $this->add($this->header);

        //cria o corpo do card
        $this->body = new Element('div');
        $this->body->class = 'card-body';
        $this->add($this->body);

        //cria o footer do card
        $this->footer = new Element('div');
        $this->footer->class = 'card-footer';
        $this->add($this->footer);

        //se for informado um titulo no construtor esse titulo fica dentro do body do card
        if ($card_title) {
            $this->card_title = new Element('h5');
            $this->card_title->class = 'card-title';
            $this->card_title->add($card_title);
            $this->body->add($this->card_title);
            if ($divider) {
                $div = new Element('hr');
                $div->{'style'} = $divider;
                $this->body->add($div);
            }
        }

    }

    public function setHeader($header, $align = 'justify')
    {   
        // create current elements copy
        $current_elements = $this->children;
        // reset childen
        $this->children = array();

        // create header
        if (!isset($this->header)) {
            $this->header = new Element('div');
            $this->header->class = 'card-header';
            $this->header->align = $align;
            $this->add($this->header);

            // add current elements after header
            foreach ($current_elements as $item) {
                $this->add($item);
            }
            
        }
        
        if (is_array($header)) {
            foreach ($header as $item) {
                $this->header->add($item);        
            }
        }
        $this->header->add($header);
        // parent::add($this->header);
    }

    public function setBody($content)
    {
        $this->body->add($content);
        //parent::add($this->body);
    }

    public function setFooter($footer)
    {
        $this->footer->add($footer);
        //parent::add($this->footer);
    }

    public function setCardTitle($card_title)
    {
        $this->card_title->add($card_title);
    }

}