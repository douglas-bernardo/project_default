<?php
namespace Library\Widgets\Dialog;

use Library\Widgets\Base\Element;

class Message 
{
    public function __construct($type, $message, $width = '100', $cssClass = '')
    {
        $div = new Element('div');
        $div->class = "{$cssClass} alert alert-{$type} alert-dismissible fade show w-{$width}";

        $div->role = 'alert';
        $button = new Element('button');
        $button->type = 'button';
        $button->class = 'close';
        $button->{'data-dismiss'} = "alert";
        $button->{'area-label'} = "Close";
        $span = new Element('span');
        $span->{'aria-hidden'} = "true";
        $span->add('&times;');
        $button->add($span);
        $div->add($button);
        $div->add($message);
        $div->show();
    }
}