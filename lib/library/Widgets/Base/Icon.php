<?php

namespace Library\Widgets\Base;

abstract class Icon
{
    const PRIMARY = 'primary';
    const SECONDARY = 'secondary';
    const SUCCESS = 'success';
    const DANGER = 'danger';
    const INFO = 'info';
    protected $base_url = 'lib/independent/icons/bootstrap/';
    protected $source;

    public function __construct(string $name)
    {
        if (file_exists($this->base_url . "{$name}.svg")) {
            $this->source = $this->base_url . "{$name}.svg";
        }
    }

    abstract function show();
    
}