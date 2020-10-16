<?php

use Library\Database\Record;
use Library\Database\Transaction;

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
        return $this->contrato->getProjeto()->numeroprojeto . '-' . $this->contrato->numero;
    }

    public function get_valor_venda()
    {
        $vl_venda = $this->contrato->getValorTotalLancamentos();
        $vl_venda = ($vl_venda == 0) ? $this->contrato->valor_venda : $vl_venda;
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

    public static function getValoresSituacao()
    {
        $conn = Transaction::get();
        // $result = $conn->query("SELECT 
        //                             st.nome as tipo,
        //                             sum(lancamentos.valor) as valor_solicitado
        //                             FROM negociacao n
        //                             left join contrato c on n.contrato_id = c.id
        //                             left join situacao st on n.situacao_id = st.id
        //                             left join 
        //                         (select 
        //                             sum(abs(vlroriginal)) as valor,
        //                             idvendaxcontrato
        //                         from 
        //                             ts_lancamentos
        //                             group by idvendaxcontrato) lancamentos 
        //                             on c.ts_idvendaxcontrato = lancamentos.idvendaxcontrato
        //                             group by 1");

        $result = $conn->query("SELECT 
                                    situacao AS tipo,
                                    sum(valor_venda) AS valor_solicitado
                                FROM 
                                    vw_analitico 
                                WHERE 
                                    ts_id_negociadora = 640054
                                GROUP BY 1");

        $dataset = [];

        foreach($result as $row){
            $dataset[ $row['tipo'] ] = $row['valor_solicitado'];
        }

        return $dataset;

    }

    public static function getOrigemTotal()
    {
        $conn = Transaction::get();
        $result = $conn->query("SELECT 
                                    o.nome AS origem,
                                    count(n.id) AS total
                                FROM negociacao n
                                LEFT JOIN origem o ON n.origem_id = o.id
                                WHERE usuario_id = 3
                                GROUP BY 1");
        $dataset = [];

        foreach($result as $row){
            $dataset[ $row['origem'] ] = $row['total'];
        }

        return $dataset;
    }
    
    public static function getEficienciaPerda()
    {
        $conn = Transaction::get();
        $result = $conn->query("SELECT 
                                    negociadora,
                                    sum(faturamento) / sum(valor_venda) AS eficiencia,
                                    sum(perda_financeira) / sum(valor_venda) AS perda_financeira
                                FROM 
                                    vw_analitico
                                WHERE 
                                    year(data_ocorrencia) = 2020
                                    AND (id_tipo_solicitacao = 2 OR id_tipo_solicitacao = 4)
                                    AND id_situacao in (1, 2, 6, 7)
                                    AND ts_id_negociadora = 640054
                                GROUP BY 1")->fetch(PDO::FETCH_ASSOC);

        $dataset = [];

        if ($result) {
            $dataset[ "Eficiência" ] = round( $result['eficiencia'] * 100, 2);
            $dataset[ "Perda Financeira" ] = round( $result['perda_financeira'] * 100 , 2 );
        }

        return $dataset;
    }

    public static function getEficienciaPerdaSeteDias()
    {
        $conn = Transaction::get();
        $result = $conn->query("SELECT 
                                    negociadora,
                                    sum(faturamento) / sum(valor_venda) AS eficiencia,
                                    sum(perda_financeira) / sum(valor_venda) AS perda_financeira
                                FROM 
                                    vw_analitico
                                WHERE 
                                    year(data_ocorrencia) = 2020
                                    AND id_tipo_solicitacao = 1
                                    AND id_situacao in (1, 2, 6, 7)
                                    AND ts_id_negociadora = 640054
                                GROUP BY 1")->fetch(PDO::FETCH_ASSOC);

        $dataset = [];

        if ($result) {
            $dataset[ "Eficiência" ] = round( $result['eficiencia'] * 100, 2);
            $dataset[ "Perda Financeira" ] = round( $result['perda_financeira'] * 100 , 2 );
        }

        return $dataset;
    }

    public static function getEficienciaMensal()
    {
        $conn = Transaction::get();
        $result = $conn->query("SELECT 
                                    ciclo_ini_num,
                                    ciclo_ini,
                                    sum(faturamento) / sum(valor_venda) AS eficiencia,
                                    sum(perda_financeira) / sum(valor_venda) AS perda_financeira
                                FROM 
                                    vw_analitico
                                WHERE 
                                    year(data_ocorrencia) = 2020
                                    AND (id_tipo_solicitacao = 2 OR id_tipo_solicitacao = 4)
                                    AND id_situacao in (1, 2, 6, 7)
                                    AND ts_id_negociadora = 640054
                                GROUP BY 1 order by ciclo_ini_num");
        $dataset = [];

        foreach($result as $row){
            $dataset[ $row['ciclo_ini'] ] = [ 'eficiencia' => $row['eficiencia'], 'perda_financeira' => $row['perda_financeira'] ];
        }

        return $dataset;
    }

    public static function getValorEmAberto()
    {
        $conn = Transaction::get();
        $result = $conn->query("SELECT 
                                    anl.negociadora AS negociadora,
                                    SUM(anl.valor_venda) AS valor_solicitado,
                                    vlab.valor_em_aberto AS valor_em_aberto
                                FROM 
                                    vw_analitico AS anl 
                                LEFT JOIN vw_valores_em_aberto vlab ON anl.id_negociadora = vlab.id_negociadora
                                WHERE anl.ano_sol = 2020 AND ( anl.ano_fin = 2020 or  isnull( anl.ano_fin ))
                                AND vlab.ano_sol = 2020
                                AND anl.id_negociadora = 3
                                GROUP BY 1")->fetch(PDO::FETCH_ASSOC);

        $dataset = [];
        // number_format($value, 2, ",", ".");
        if ($result) {
            $dataset[ "negociadora" ] =  $result['negociadora'];
            $dataset[ "valor_solicitado" ] =  number_format( $result['valor_solicitado'], 2, ",", ".");
            $dataset[ "valor_em_aberto" ] =  number_format( $result['valor_em_aberto'], 2, ",", ".");
            $dataset[ "percentual" ] = round( ( $result['valor_em_aberto'] / $result['valor_solicitado'] ) * 100 , 2 ) . '%';
        }

        return $dataset;
    }

}