<?php

use Services\Controllers\CmMapControl;

class TestService
{
    public static function getData($param)
    {
        try {
            $cm = new CmMapControl;
            $result = $cm->getData($param);
            return $result;

        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}