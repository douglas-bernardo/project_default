<?php
namespace Services\Models;

use Services\Database\ActiveRecord;

class OcorrenciaTS extends ActiveRecord
{
    private $fields = [
        "numero_ocorrencia", 
        "status", 
        "ts_motivo_id", 
        "dtocorrencia",
        "ts_cliente_id",
        "nome_cliente",
        "numero_projeto",
        "numero_contrato",
        "valor_venda",
        "ts_usuario_resp_id",
        "ts_usuario_resp_nome"
    ];

    public function __construct() 
    {
        $sql = getStringSql('cm_ocorrencias_ts_renegociacao_app');        
        parent::__construct($sql, $this->fields);
    }

    public function getNumResults()
    {
        return self::$nresults;
    }

    public function getFail()
    {
        return self::$fail;
    }
}
