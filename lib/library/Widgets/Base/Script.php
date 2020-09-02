<?php

namespace Library\Widgets\Base;

use Library\Widgets\Base\Element;

class Script
{
    /**
     * Create a script
     * @param $code source code
     */
    public static function create( $code, $show = TRUE, $timeout = null )
    {
        if ($timeout)
        {
            $code = "setTimeout( function() { $code }, $timeout )";
        }
        
        $script = new Element('script');
        $script->{'language'} = 'JavaScript';
        // $script->setUseSingleQuotes(TRUE);
        // $script->setUseLineBreaks(FALSE);
        $script->add( str_replace( ["\n", "\r"], [' ', ' '], $code) );
        if ($show)
        {
            $script->show();
        }
        return $script;
    }
    
    /**
     * Import script
     * @param $script Script file name
     */
    public static function importFromFile( $script, $show = TRUE, $timeout = null )
    {
        Script::create('$.getScript("'.$script.'");', $show, $timeout);
    }
}
