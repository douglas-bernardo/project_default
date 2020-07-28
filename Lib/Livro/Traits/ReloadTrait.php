<?php
namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Widgets\Dialog\Message;
use Exception;
use Livro\Log\LoggerTXT;

trait ReloadTrait
{
    function onReload($param = null)
    {
        try{

            $order = ($this->order_param ? $this->order_param : 'id');

            Transaction::open($this->connection);
            
            //Transaction::setLogger(new LoggerTXT('tmp/onReloadTrait.txt'));

            $repository = new Repository($this->activeRecord);  //cria um repositório
            
            $criteria = new Criteria;
            $criteria->setProperties($param);
            $criteria->setProperty('limit', 15);
            $criteria->setProperty('order', $order);
            
            if( isset($this->filter) ) {
                if (is_array($this->filter)) {
                    foreach ($this->filter as $f) {
                        $criteria->add($f);      
                    }
                } else {
                    $criteria->add($this->filter);
                }
            }
            
            //carrega os objetos que satisfazem o critério
            $objects = $repository->load($criteria);
            $this->datagrid->clear();
            
            if($objects){
                foreach($objects as $object){
                    //adiciona objeto no datagrid
                    $this->datagrid->addItem($object);
                }
            }

            $criteria->resetProperties();
            $count = $repository->count($criteria);

            if (isset($this->pageNavigation)) {
                $this->pageNavigation->setCount($count);
                $this->pageNavigation->setProperties($param);
            }
            Transaction::close();
            $this->loaded = true;
        }
        catch(Exception $e){
            new Message('warning', $e->getMessage());
        }
    }
}
