<?php

use Livro\Control\Page;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Wrapper\DatagridWrapper;

class NegociacaoList extends Page
{
    private $loaded;
    private $datagrid;

    public function __construct() 
    {
        parent::__construct();

        $this->datagrid = new DatagridWrapper(new Datagrid);

        $id = new DatagridColumn('id', 'id', 'center','10%');
        $ocorrencia = new DatagridColumn('ocorrencia', 'ocorrencia', 'center','20%');
        $data = new DatagridColumn('data', 'data', 'center','10%');
        $finalizada = new DatagridColumn('finalizada', 'finalizada', 'center','10%');

        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($ocorrencia);
        $this->datagrid->addColumn($data);
        $this->datagrid->addColumn($finalizada);

        $finalizada->setTransformer(array($this, 'setColor'));

        $this->datagrid->createModel();

        parent::add($this->datagrid);

    }

    public function setColor($value, $row)
    {
        if ($value == 'não') {
            $row->children[1]->{'style'} = "background: green";
        }
        return $value;
    }

    function onReload()
    {
        $this->datagrid->clear();

        $neg1 = new stdClass;
        $neg1->id = 1;
        $neg1->ocorrencia = 50000;
        $neg1->data = '10-01-2020';
        $neg1->finalizada = 'sim';
        $this->datagrid->addItem($neg1);

        $neg2 = new stdClass;
        $neg2->id = 2;
        $neg2->ocorrencia = 50002;
        $neg2->data = '10-02-2020';
        $neg2->finalizada = 'não';
        $this->datagrid->addItem($neg2);
        
        $neg3 = new stdClass;
        $neg3->id = 3;
        $neg3->ocorrencia = 50003;
        $neg3->data = '10-03-2020';
        $neg3->finalizada = 'sim';
        $this->datagrid->addItem($neg3);

        $neg4 = new stdClass;
        $neg4->id = 4;
        $neg4->ocorrencia = 50004;
        $neg4->data = '10-04-2020';
        $neg4->finalizada = 'sim';
        $this->datagrid->addItem($neg4);

        $neg5 = new stdClass;
        $neg5->id = 5;
        $neg5->ocorrencia = 50005;
        $neg5->data = '10-05-2020';
        $neg5->finalizada = 'sim';
        $this->datagrid->addItem($neg5);
    }

    function show()
    {
        if(!$this->loaded){
            $this->onReload();
        }
        parent::show();
    }

}