<?php

use Library\Database\Record;

class Ocorrencia extends Record
{
    const TABLENAME = 'ocorrencia';

    public function get_descricao()
    {
        return (new Motivo())->loadBy('idmotivots', $this->ts_motivo_id)->descricao;
    }
}