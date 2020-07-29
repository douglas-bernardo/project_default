<?php
namespace Library\Widgets\Dialog;

use Library\Widgets\Base\Element;

class GenericModal extends Element
{
    private $dialog;
    private $content;
    private $header;
    private $body;
    private $footer;
    private $close_oparation;

    public function __construct($target_id)
    {
        parent::__construct('div');

        // principal
        $this->{'class'} = "modal";
        $this->{'id'} = $target_id;
        $this->{'tabindex'} = '-1';
        $this->{'role'} = 'dialog';
        $this->{'aria-labelledby'} = 'exampleModalLabel';
        $this->{'aria-hidden'} = "true";

        //dialog
        $this->dialog = new Element('div');
        $this->dialog->class = 'modal-dialog modal-dialog-centered modal-lg';
        $this->dialog->role = 'document';

        //content
        $this->content = new Element('div');
        $this->content->class = 'modal-content'; 

        //adiciona o conteudo ao dialog
        $this->dialog->add($this->content);

        //adiciona o conteudo ao principal
        parent::add($this->dialog);

    }
    
    public function add($message)
    {
        $this->body->add($message);
    }

    public function setHeader($content)
    {
        //header
        $this->header = new Element('div');
        $this->header->class = 'modal-header';

        //title
        $label = new Element('h5');
        $label->class = 'modal-title';
        $label->id = 'exampleModalLabel';
        $label->add($content);

        //MODAL close button 
        $button = new Element('button');
        $button->type = 'button';
        $button->class = 'close';
        $button->{'data-dismiss'} = 'modal';
        $button->{'aria-label'} = 'Close';
        $span = new Element('span');
        $span->{'aria-hidden'} = 'true';
        $span->add('&times;');
        $button->add($span);

        // add title and button on header
        $this->header->add($label);
        $this->header->add($button);
        $this->content->add($this->header);

    }

    public function setBody($content)
    {
        //body
        $this->body = new Element('div');
        $this->body->class = 'modal-body';
        $this->body->add($content);
        $this->content->add($this->body);
    }

    /**
     * Additional content on footer
     *
     * @param [mixed] $content
     * @return void
     */
    public function setFooter($content)
    {
        // DEFAULT: footer CLOSE
        $this->footer = new Element('div');
        $this->footer->class = 'modal-footer';
        $this->footer->add($content);
        
        if ($this->close_oparation) {
            $this->footer->add($this->close_oparation);
        } 
        
        $this->content->add($this->footer);
    }

    public function setDefaultCloseOperation($label = 'Cancelar', $class = 'btn btn-secondary')
    {
        $this->close_oparation = new Element('button');
        $this->close_oparation->type = 'button';
        $this->close_oparation->class = $class;
        $this->close_oparation->{'data-dismiss'} = "modal";
        $this->close_oparation->add($label);
    }

}