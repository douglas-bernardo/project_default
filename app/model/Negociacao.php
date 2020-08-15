<?php

use Library\Database\Record;

class Negociacao extends Record
{
    const TABLENAME = 'negociacao';

    public function get_numero_ocorrencia(): ? string
    {
        return $this->getOcorrencia()->numero_ocorrencia;
    }

    public function get_nome_cliente(): ? string
    {
        return $this->getContrato()->getCliente()->nome;
    }

    public function get_projeto_contrato(): ? string
    {
        $c = $this->getContrato();
        if (isset($c)) {
            return $c->projeto . '-' . $c->numero;
        }
        return null;
    }

    public function get_valor_venda()
    {
        $c = $this->getContrato();
        if (isset($c)) {
            $valor = $c->getValorVenda();
            if ($valor) {
                return  $valor;
            } else {
                return 0;
            }
        }
    }

    public function get_tipo_solicitacao(): string
    {
        return (new TipoSolicitacao($this->tipo_solicitacao_id))->nome;
    }

    public function get_origem(): string
    {
        return (new Origem($this->origem_id))->nome;
    }

    public function get_situacao(): string
    {
        return (new Situacao($this->situacao_id))->nome;
    }

    /**
     * Retorna a ocorrência relacionada a negociação. 
     *
     * @return Ocorrencia
     */
    public function getOcorrencia(): ? Ocorrencia
    {
        return (new Ocorrencia($this->ocorrencia_id));
    }

    /**
     * Retorna o contrato relacionado a negociação. 
     *
     * @return Contrato
     */
    public function getContrato(): ? Contrato
    {
        return (new Contrato($this->contrato_id));
    }

}