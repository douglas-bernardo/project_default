<?php

use Library\Database\Record;

class Negociacao extends Record
{
    const TABLENAME = 'negociacao';

    public function get_numero_ocorrencia()
    {
        return $this->getOcorrencia()->numero_ocorrencia;
    }

    public function get_data_ocorrencia()
    {
        return $this->getOcorrencia()->dtocorrencia;
    }

    public function get_nome_cliente()
    {
        return $this->getOcorrencia()->nome_cliente;
    }
    
    public function get_proj_contrato()
    {
        $ocorrencia = $this->getOcorrencia();
        $pro_contrato = $ocorrencia->numero_projeto . '-' . $ocorrencia->numero_contrato;
        return $pro_contrato;
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