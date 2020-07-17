<?php
namespace Livro\Widgets\Datagrid;

use Livro\Control\Action;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Container\TableRow;
use Livro\Widgets\Base\Element;

class Datagrid extends Table
{
    private $columns;
    private $actions;
    private $actionWidth;
    private $rowcount;
    private $headerRow;
    private $bodyRow;
    private $actionsPosition  = 'left';

    public function addColumn(DatagridColumn $object)
    {
        $this->columns[] = $object;
    }
    //public function addAction(DatagridAction $object)
    public function addAction($object, $actionWidth = '40px')
    {
        $this->actions[] = $object;
        $this->actionWidth = $actionWidth;
    }

    function clear()
    {
        //faz uma cópia do cabeçalho
        $copy = $this->children[0];
        //inicializa o vetor de linhas
        $this->children = array();
        //acrescenta novamente o cabeçalho
        $this->children[] = $copy;
        //zera a contagem de linhas
        $this->rowcount = 0;
    }

    public function createModel()
    {
        //header <thead>
        $thead = new Element('thead');
        $thead->class = 'thead-light';
        parent::add($thead);
        //adiciona uma linha ao cabeçalho
        $row = new Element('tr');
        $thead->add($row);

        if($this->actions){
            foreach ($this->actions as $action){
                $celula = new Element('th');
                $celula->{'width'} = $this->actionWidth;
                $row->add($celula);
            }
        }

        //adiciona as células <th> para os titulos das colunas do cabeçalho
        if($this->columns){
            //percorre as colunas do cabeçalho
            foreach($this->columns as $column){
                //obtém as propriedades da coluna (passados no construtor de DatagridColumn)
                $name = $column->getName();
                $label = $column->getLabel();
                $align = $column->getAlign();
                $width = $column->getWidth();

                $celula = new Element('th');
                $celula->scope = "col";                
                $celula->add($label);
                //adiciona a célula com a coluna
                $row->add($celula);
                $celula->style = "text-align:$align";//melhorado

                if ( $width == '') {
                    $celula->{'style'} = "display:none"; 
                } else {
                    $celula->width = $width;
                }

                //verifica se a coluna do cabeçalho tem uma ação
                if($column->getAction()){
                    $url = $column->getAction();
                    $celula->onclick = "document.location='{$url}'";
                }
            }
        }

    }

    public function addItem($object)
    {
        //adiciona uma linha a Datagrid
        $row = parent::addRow();
        $row->scope = "row";

        //verifica se a listagem contém ações
        if($this->actions){
            //percorre as ações
            foreach($this->actions as $action){
                //teste
                $link = new Element('a');

                //obtem as propriedades da ação                
                $label = $action->getLabel();
                $image = $action->getImage();
                $field = $action->getField();//nome do campo

                //obtem o valor do campo do objeto que será passado adiante
                $key = $object->$field;

                if ($action instanceof Action){
                    $url = $action->serialize();
                    $link->href = "{$url}&key={$key}&{$field}={$key}";
                }else{
                    $link->id = "ajaxDel";
                    $link->onclick = "{$action->getFunction()}($key, '{$action->getUrl()}', '{$action->getActiveRecord()}')";
                }

                //verifica se o link será com imagem ou com texto
                if ($image) {
                    $img = new Element('img');
                    $img->src = "App/Images/$image";
                    $img->title = $label;
                    $link->add($img);
                } else {
                    $link->{'class'} = $action->getClass();
                    $link->{'style'} = $action->getStyle();
                    $link->add($label);
                }
                //adiciona a celula a linha
                $row->addCell($link);
                $row->align = 'center';//alinha as ações * adaptado
            }
        }

        if($this->columns){
            //percorre as colunas da Datagrid
            foreach($this->columns as $column){
                //obtém as propriedades da coluna (passadas no construtor de DatagridColumn)
                $name     = $column->getName();
                $align    = $column->getAlign();
                $width    = $column->getWidth();
                $function = $column->getTransformer();
                $data     = $object->$name;

                //verifica se há função para transformar os dados
                if($function){
                    //aplica a função sobre os dados
                    $data = call_user_func($function, $data, $row);
                }

                //adiciona a celula a linha
                $celula = $row->addCell($data);
                $celula->align = $align;

                if ( $width == '') {
                    $celula->{'style'} = "display:none"; 
                } else {
                    $celula->width = $width;
                }
            }
        }
        //incrementa o contador de linhas
        $this->rowcount++;
        return $row;
    }

    public function getActions()
    {
        return $this->actions;
    }
}