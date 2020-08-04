<?php
namespace Services\Controllers;

use Services\Models\CmMap;

class CmMapControl
{
    public function getData($param)
    {
        $cm_data = new CmMap('cm_resumo_financeiro_dtvenda_app', array('PARAM_IDVENDAXCONTRATO', $param['idvendaxcontrato']));
        return $cm_data->load();
    }

    public function test()
    {
        return "direct access";
    }
}