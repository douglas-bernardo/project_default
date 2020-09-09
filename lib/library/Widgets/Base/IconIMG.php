<?php

namespace Library\Widgets\Base;

class IconIMG extends Icon
{
    
    /** @var Image */
    private $svg;

    public function __construct(string $name, string $size = '28')
    {
        parent::__construct($name);
        $this->svg = new Image($this->source);
        $this->svg->width = $size;
        $this->svg->height = $size;
    }

    public function show()
    {
        $this->svg->show();
    }
    
}