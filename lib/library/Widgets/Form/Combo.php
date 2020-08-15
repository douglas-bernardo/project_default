<?php
namespace Library\Widgets\Form;

use Library\Widgets\Base\Element;

class Combo extends Field implements FormElementInterface
{
    private $items;//array contendo os itens do combo
    private $default;

    public function __construct($name, $class = 'combo', $default = '')
    {
        parent::__construct($name);
        $this->tag = new Element('select', true);
        $this->tag->{'class'} = $class;    //classe CSS
        $this->default = $default;        
    }

    public function addItems($items)
    {
        $this->items = $items;
    }

    public function show()
    {
        //atribui as propriedades da tag
        $this->tag->name = $this->name; //nome da tag
        $this->tag->style = "width:{$this->size}px";//tamanho em pixels

        //cria uma tag option com um valor padrão
        $option = new Element('option');
        $option->add($this->default);
        $option->value = "0"; //valor da tag
        $option->setMetaData('disabled selected');

        //adiciona a opção a combo
        $this->tag->add($option);

        if ($this->items) {
            //percorre os itens adicionados
            foreach ($this->items as $chave => $item) {
                //cria uma tag <option> para o item
                $option = new Element('option');
                $option->value = $chave;//define o indice da opção
                $option->add($item);//adiciona o texto da opção

                //caso seja a opção selecionada
                if ($chave == $this->value) {
                    $option->selected = 1;
                }
                //adiciona a opção a combo
                $this->tag->add($option);
            }
        }
        //verifica se o campo é editável
        if (!parent::getEditable()) {
            $this->tag->readonly = "1";
        }
        $this->tag->show();
    }
}
