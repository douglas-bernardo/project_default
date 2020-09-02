<?php

use Library\Control\Page;
use Library\Widgets\Base\Element;
use Library\Widgets\Container\Breadcrumb;
use Library\Widgets\Container\Card;
use Library\Widgets\Container\Row;

class HomeControl extends Page
{
    private $breadcrumb;

    public function __construct() {
        parent::__construct();

        $this->breadcrumb = new Breadcrumb;
        $this->breadcrumb->addBreadCrumbItem('Home');
        parent::add($this->breadcrumb);

        // //criando uma row Bootstrap:
        // $row = new Row;

        // //criando um card:
        // $card = new Card('Special title treatment');
        // $card->setHeader('Featured');
        // $card->setBody('With supporting text below as a natural lead-in to additional content.');
        // $card->setFooter('teste');

        // // //criando um card2:
        // $card2 = new Card('Special title treatment');
        // $card2->setHeader('Featured');
        // $card2->setBody('With supporting text below as a natural lead-in to additional content.');
        // $card2->setFooter('teste');


        // // //criando uma col Bootstrap:
        // $col = $row->addCol($card);// add conteudo a coluna
        // $col->class = 'col-sm-6';

        // $col2 = $row->addCol($card2);// add conteudo a coluna
        // $col2->class = 'col-sm-6';

        // parent::add($row);        

        // Power BI
        $src = 'https://app.powerbi.com/view?r=eyJrIjoiYjVlNTc5ZjYtZjgxNC00YzIzLTkzYzQtYjUxMTQ1YjhkNjkwIiwidCI6IjVkNGI5OGM5LTM5MmYtNGM4Ny05OWE5LTUyNjA3ODE0MDUxZCJ9';
        $iframe = new Element('iframe');
        $iframe->{'class'} = 'embed-responsive-item';
        $iframe->{'src'} = $src;
        $iframe->{'frameborder'} = 0;
        $iframe->{'allowFullScreen'} = true;

        //$frame = '<iframe class="embed-responsive-item" src="' . $src . '" frameborder="0" allowFullScreen="true"></iframe>';
        $div =  new Element('div');
        $div->{'class'} = 'embed-responsive embed-responsive-16by9';
        $div->add($iframe);
               
        parent::add($div);
        
    }
}
