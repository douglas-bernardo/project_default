<?php

use Library\Database\Record;

class Ocorrencia extends Record
{
    const TABLENAME = 'ocorrencia';

    /**
     * Motivo da abertura da ocorrência
     *
     * @return string|null
     */
    public function get_motivo(): ? string
    {
        return (new Motivo())->loadBy('idmotivots', $this->idmotivots)->descricao;
    }

    /**
     * Produto relacionado ao contrato da ocorrência
     *
     * @return string|null
     */
    public function get_produto(): ? string
    {
        return (new Projeto())->loadBy('idprojetots', $this->idprojetots)->nomeprojeto;
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