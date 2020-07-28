<?php

use Livro\Database\Record;

class Negociacao extends Record
{
    const TABLENAME = 'negociacao';

    public function get_numero_ocorrencia()
    {
        return (new Ocorrencia($this->ocorrencia_id))->numero_ocorrencia;
    }

    public function get_data_ocorrencia()
    {
        return (new Ocorrencia($this->ocorrencia_id))->dtocorrencia;
    }

    public function get_cliente()
    {
        return (new Ocorrencia($this->ocorrencia_id))->nome_cliente;
    }

    public function get_proj_contrato()
    {
        $ocorrencia = new Ocorrencia($this->ocorrencia_id);
        $pro_contrato = $ocorrencia->numero_projeto . '-' . $ocorrencia->numero_contrato;
        return $pro_contrato;
    }

    public function get_valor_venda()
    {
        return (new Ocorrencia($this->ocorrencia_id))->valor_venda;
    }

    public function get_tipo_solicitacao()
    {
        return (new TipoSolicitacao($this->tipo_solicitacao_id))->nome;
    }

    public function get_origem()
    {
        return (new Origem($this->origem_id))->nome;
    }

    public function get_situacao()
    {
        return (new Situacao($this->situacao_id))->nome;
    }


    /**
     * Return object 
     *
     * @return Ocorrencia
     */
    public function getOcorrencia()
    {
        return new Ocorrencia($this->ocorrencia_id);
    }


}