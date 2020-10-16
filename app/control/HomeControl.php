<?php

use Library\Control\Page;
use Library\Widgets\Container\Breadcrumb;
use Library\Widgets\Container\Card;
use Library\Widgets\Container\Row;

class HomeControl extends Page
{
    private $breadcrumb;

    public function __construct() 
    {
        parent::__construct();

        $this->breadcrumb = new Breadcrumb;
        $this->breadcrumb->addBreadCrumbItem('Home');
        parent::add($this->breadcrumb);        

        // chart.js view
        // $hbox = new HBox;        
        // $hbox->add( new ValorSituacaoChart )->style.=';width:48%';
        // $hbox->add( new OrigemTotalChart )->style.=';width:48%;';
        // parent::add($hbox);

        $row1 = new Row;

        $card = new Card();
        $card->setHeader('Valor Recebido Por Situação')->{'style'} = 'background-color: #FFF; font-size: 12px';
        $card->setBody( new ValorSituacaoChart );
        $card->setFooter(null);
        $col = $row1->addCol($card);// add conteudo a coluna
        $col->class = 'col-sm-6';

        $card2 = new Card();
        $card2->setHeader('Principais Origens')->{'style'} = 'background-color: #FFF; font-size: 12px';
        $card2->setBody( new OrigemTotalChart );
        $card2->setFooter(null);
        $col2 = $row1->addCol($card2);// add conteudo a coluna
        $col2->class = 'col-sm-6';

        parent::add($row1);

        // level 2
        $row2 = new Row;
        $eficiencia_perda_graf =  new Card();
        $eficiencia_perda_graf->setHeader('Eficiência x Perda Acumulado (%)')->{'style'} = 'background-color: #FFF; font-size: 12px';
        $eficiencia_perda_graf->setBody( new EficienciaPerdaFinChart );
        $eficiencia_perda_graf->setFooter(null);
        $col3 = $row2->addCol($eficiencia_perda_graf);
        $col3->class = 'col-sm-6';

        $eficiencia_perda_sete_dias_graf =  new Card();
        $eficiencia_perda_sete_dias_graf->setHeader('Eficiência x Perda Acumulado 7 Dias (%)')->{'style'} = 'background-color: #FFF; font-size: 12px';
        $eficiencia_perda_sete_dias_graf->setBody( new EficienciaPerdaFinSeteDiasChart );
        $eficiencia_perda_sete_dias_graf->setFooter(null);
        $col4 = $row2->addCol($eficiencia_perda_sete_dias_graf);
        $col4->class = 'col-sm-6';

        parent::add($row2);

        // level 3
        $row3 = new Row;
        $efi_mensal = new Card();
        $efi_mensal->setHeader('% Eficiência x Perda Mensal')->{'style'} = 'background-color: #FFF; font-size: 12px';
        $efi_mensal->setBody( new EficienciaPerdaMensalChart );
        $efi_mensal->setFooter(null);
        $col = $row3->addCol($efi_mensal);
        $col->class = 'col';        
        parent::add($row3);


        //level 4
        $row4 = new Row;
        $em_aberto = new Card();
        $em_aberto->setHeader('Perncentual em aberto')->{'style'} = 'background-color: #FFF; font-size: 12px';
        $em_aberto->setBody( new ValorEmAbertoTable );
        $em_aberto->setFooter(null);
        $col = $row4->addCol($em_aberto);
        $col->class = 'col';        
        parent::add($row4);


    }        
}
