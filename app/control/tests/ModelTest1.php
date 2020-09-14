<?php

use Library\Control\Page;
use Library\Widgets\Base\IconSVG;

class ModelTest1 extends Page
{
    public function show()
    {
        var_dump($_SESSION);
    }
}