<?php

namespace Library\Widgets\Base;

class IconSVG extends Icon
{
    private $svg;

    public function __construct(string $name, string $size = '1em', string $class = null)
    {
        parent::__construct($name);

        $this->svg = simplexml_load_file($this->source);
        
        // var_dump($svg);
        // echo PHP_EOL;
        // var_dump($svg->attributes()['width']);
        // var_dump($svg->attributes()['height']);
        // var_dump($svg->attributes()['class']);
        
        $this->svg->attributes()['width'] = $size;
        $this->svg->attributes()['height'] = $size;
        if ($class) {
            $this->svg->attributes()['class'] .= ' text-' . $class;
        }
        //var_dump($svg->attributes()['class']);
    }

    public function show()
    {
        echo $this->svg->asXML();
    }

}