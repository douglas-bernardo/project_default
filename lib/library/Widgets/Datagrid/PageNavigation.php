<?php
namespace Library\Widgets\Datagrid;

use Library\Widgets\Base\Element;

class PageNavigation 
{
    private $limit;
    private $count;
    private $page;
    private $first_page;
    private $action;

    public function setLimit($limit)
    {
        $this->limit = (int) $limit;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setCount($count)
    {
        $this->count = (int) $count;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function setPage($page)
    {
        $this->page = (int) $page;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setFirstPage($first_page)
    {
        $this->first_page = (int) $first_page;
    }

    public function setProperties($properties)
    {
        $page       = isset($properties['page'])   ? $properties['page']   : 1;
        $first_page = isset($properties['first_page']) ? $properties['first_page']: 1;        
        $this->setPage($page);
        $this->setFirstPage($first_page);
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function show()
    {

        $first_page = isset($this->first_page) ? $this->first_page : 1;
        $page_size  = isset($this->limit) ? $this->limit : 10;
        $max = 10;
        $registros = $this->count;
        
        if (!$registros)
        {
            $registros = 0;
        }
        
        if ($page_size > 0)
        {
            $pages = (int) ($registros / $page_size) - $first_page +1;
        }
        else
        {
            $pages = 1;
        }
        
        $resto = 0;
        if ($page_size>0)
        {
            $resto = $registros % $page_size;
        }
        
        $pages += $resto > 0 ? 1 : 0;
        $last_page = min($pages, $max);

        //structure
        $nav = new Element('nav');
        $ul = new Element('ul');
        $ul->{'class'} = 'pagination pagination-sm';
        $ul->{'style'} = 'margin-bottom: 0;';

        $nav->add($ul);

        if ($first_page > 1) {
            // first
            $item = new Element('li');
            $link = new Element('a');
            $span = new Element('span');
            $item->{'class'} = 'page-item';            
            $link->{'class'} = 'page-link';            
            $this->action->setParameter('offset', 0);
            $this->action->setParameter('limit', $page_size);
            $this->action->setParameter('page', 1);
            $this->action->setParameter('first_page', 1);
            $link->{'href'} = $this->action->serialize();
            $link->{'title'} = 'Primeira';
            $link->{'aria-label'} = 'Previous';            
            $span->{'aria-hidden'} = 'true';            
            $span->add('&laquo;'); 
            $item->add($link);
            $link->add($span);
            $ul->add($item);

            // previous
            $item = new Element('li');
            $link = new Element('a');
            $span = new Element('span');
            $link->{'aria-label'} = 'Previous';
            $ul->add($item);
            $item->add($link);
            $link->add($span);
            $this->action->setParameter('offset', ($first_page - $max -1) * $page_size);
            $this->action->setParameter('limit',  $page_size);
            $this->action->setParameter('page',   $first_page - $max);
            $this->action->setParameter('first_page', $first_page - $max);
            $link->{'class'} = "page-link";
            $link->{'href'} = $this->action->serialize();
            $span->add('Aterior');

        }

        // active pages
        for ($n = $first_page; $n <= $last_page + $first_page - 1; $n++) { 
            $offset = ($n - 1) * $page_size;
            $item = new Element('li');
            $link = new Element('a');
            $span = new Element('span');
            $ul->add($item);
            $item->add($link);
            $link->add($span);
            $span->add($n);
            $this->action->setParameter('offset', $offset);
            $this->action->setParameter('limit', $page_size);
            $this->action->setParameter('page', $n);
            $link->{'href'} = $this->action->serialize();
            $link->{'class'} = 'page-link';            
            if ($this->page == $n) {
                $item->{'class'} = 'active page-item';
            }
        }

        // inactive pages/placeholders
        for ($z=$n; $z<=10; $z++) {
            $item = new Element('li');
            $link = new Element('a');
            $span = new Element('span');
            $item->{'class'} = 'off page-item';
            $link->{'class'} = 'page-link';
            $ul->add($item);
            $item->add($link);
            $link->add($span);
            $span->add($z);
        }

        if ($pages > $max) {
            // next
            $first_page = $n;
            $item = new Element('li');
            $link = new Element('a');
            $span = new Element('span');
            $link->{'aria-label'} = "Next";
            $ul->add($item);
            $item->add($link);
            $link->add($span);
            $this->action->setParameter('offset',  ($n -1) * $page_size);
            $this->action->setParameter('limit',   $page_size);
            $this->action->setParameter('page',    $n);
            $this->action->setParameter('first_page', $n);
            $link->{'class'}     = "page-link";
            $link->{'href'}      = $this->action->serialize();            
            $span->add('Próxima'); //$span->add('&raquo;');
            
            // last
            $item = new Element('li');
            $link = new Element('a');
            $span = new Element('span');
            $link->{'aria-label'} = "Next";
            $ul->add($item);
            $item->add($link);
            $link->add($span);
            $this->action->setParameter('offset',  ceil($registros / $page_size)* $page_size - $page_size);
            $this->action->setParameter('limit',   $page_size);
            $this->action->setParameter('page',    ceil($registros / $page_size));
            $this->action->setParameter('first_page', (int) ($registros / ($page_size *10)) *10 +1);
            $link->{'class'} = "page-link";
            $link->{'href'} = $this->action->serialize();
            $link->{'title'} = 'Última';        
            $span->add('&raquo;');
        }
        $nav->show();
    }

}