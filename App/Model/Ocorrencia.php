<?php

use Livro\Database\Criteria;
use Livro\Database\Filter;
use Livro\Database\Record;
use Livro\Database\Repository;
use Livro\Database\Transaction;

class Ocorrencia extends Record
{
    const TABLENAME = 'ocorrencia';

    public function get_descricao()
    {
        return (new Motivo())->loadBy('idmotivots', $this->ts_motivo_id)->descricao;
    }
}