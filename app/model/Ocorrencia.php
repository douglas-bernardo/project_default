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

    /**
     * Retorna a última ocorrência salva no banco de dados
     *
     * @return Ocorrencia|null
     */
    public function getLastStored(): ? Ocorrencia
    {
        $ocorrencia = $this->load($this->getLast());
        if ($ocorrencia) {
            return $ocorrencia;
        }
        return null;
    }

}