<?php
namespace Library\Traits;

use Library\Database\Transaction;
use Library\Database\Repository;
use Library\Database\Criteria;
use Library\Widgets\Dialog\Message;

use Exception;
use Library\Log\LoggerTXT;

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
            $criteria->setProperty('limit', 10);
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
            $this->total_registers = $count;

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
