<?php
namespace Library\Traits;

use Library\Control\Action;
use Library\Widgets\Dialog\Message;

trait ConfirmTrait
{
    public function confirm($param)
    {
        $confirm_type = $param['type'];
        $class = $param['activeRecord'];
        $actConfirm = new Action(array(new $class, 'onReload'));
        new Message('success', "Registro {$confirm_type} com sucesso!");
    }
}
