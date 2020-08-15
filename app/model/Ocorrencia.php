<?php

use Library\Database\Record;

class Ocorrencia extends Record
{
    const TABLENAME = 'ocorrencia';

    /**
     * Motivo da abertura da ocorrência
     *
     * @return string
     */
    public function get_descricao(): string
    {
        return (new Motivo())->loadBy('idmotivots', $this->idmotivots)->descricao;
    }

}