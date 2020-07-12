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

        // var_dump($param);
        // die;

        try{

            //$param_criteria = $param;            
            Transaction::open($this->connection); 
            
            Transaction::setLogger(new LoggerTXT('/var/www/project-default/tmp/log_reloadTrait.txt'));
            

            $repository = new Repository($this->activeRecord);  //cria um repositório
            
            $criteria = new Criteria;
            $criteria->setProperties($param);
            $criteria->setProperty('limit', 10);
            $criteria->setProperty('order', 'id');
            
            if(isset($this->filter)){
                $criteria->add($this->filter);
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
        }
        catch(Exception $e){
            new Message('warning', $e->getMessage());
        }
    }
}
