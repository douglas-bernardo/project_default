<?php

use Library\Database\Record;

/**
 * Esta classe representa uma negociação gerada através de uma ocorrência no TS
 * A existencia de uma negociação está diretamente relacionada a uma ocorrência
 * por tanto a classe armazena uma referêcnia ditera a uma ocorrência a través do 
 * atributo ocorrencia.
 */
class Negociacao extends Record
{
    const TABLENAME = 'negociacao';

    /** @var Ocorrencia  */
    private $ocorrencia;

    /** @var Contrato */
    private $contrato;

    public function __construct($id = null) 
    {
        parent::__construct($id);
        $this->ocorrencia = new Ocorrencia($this->ocorrencia_id);
        $this->contrato = new Contrato($this->contrato_id);
    }

    /** @return string|null */
    public function get_numero_ocorrencia(): ? string
    {
        return $this->ocorrencia->numero_ocorrencia;
    }

    /** @return string|null */
    public function get_data_ocorrencia(): ? string
    {
        return $this->ocorrencia->dtocorrencia;
    }

    /** @return string|null */
    public function get_nome_cliente(): ? string
    {
        return $this->contrato->getCliente()->nome;
    }

    /** @return string */
    public function get_projeto_contrato(): ? string
    {
        return $this->contrato->projeto . '-' . $this->contrato->numero;
    }

    public function get_valor_venda()
    {
        $vl_venda = $this->contrato->getValorTotalLancamentos();
        $vl_venda = ($vl_venda == 0) ? $this->ocorrencia->valor_venda : $vl_venda;
        return $vl_venda;
    }

    /** @return string */
    public function get_tipo_solicitacao(): string
    {
        return (new TipoSolicitacao($this->tipo_solicitacao_id))->nome;
    }

    /** @return string */
    public function get_origem(): string
    {
        return (new Origem($this->origem_id))->nome;
    }

    /** @return string */
    public function get_situacao(): string
    {
        return (new Situacao($this->situacao_id))->nome;
    }

    /**
     * Undocumented function
     *
     * @return Ocorrencia|null
     */
    public function getOcorrencia(): ? Ocorrencia
    {
        return $this->ocorrencia;
    }

    /**
     * Undocumented function
     *
     * @return Contrato|null
     */
    public function getContrato(): ? Contrato
    {
        return $this->contrato;
    }

}