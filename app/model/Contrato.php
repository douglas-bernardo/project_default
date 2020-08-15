<?php

use Library\Database\Record;
use Library\Database\Transaction;

class Contrato extends Record
{
    const TABLENAME = 'contrato';

    /**
     * Cliente relacionado ao contrato
     *
     * @return Cliente|null
     */
    public function getCliente(): ? Cliente
    {
        return (new Cliente($this->cliente_id));
    }

    public function getValorVenda()
    {
        $conn = Transaction::get();
        $sql = "select abs(sum(vlroriginal)) as valor_venda from ts_lancamentos where idvendaxcontrato = " . $this->ts_idvendaxcontrato;
        $valor = $conn->query($sql)->fetchColumn();
        if ($valor) {
            return $valor;
        }
        return 0;
    }

    public function getValorPago()
    {
        $conn = Transaction::get();
        $sql = "select sum(valorpagocar) from ts_lancamentos where idvendaxcontrato = " . $this->ts_idvendaxcontrato . " and statuscar = 'Quitado'";
        $valor = $conn->query($sql)->fetchColumn();
        if ($valor) {
            return $valor;
        }
        return 0;        
    }

}