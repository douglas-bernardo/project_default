<?php

use Library\Database\Criteria;
use Library\Database\Filter;
use Library\Database\Record;
use Library\Database\Repository;
use Library\Database\Transaction;

class Contrato extends Record
{
    const TABLENAME = 'contrato';

    /**
     * Parcelas lançadas para o contrato
     *
     * @var array
     */
    private $lancamentos = [];

    /**
     * Cliente relacionado ao contrato
     *
     * @return Cliente|null
     */
    public function getCliente(): ? Cliente
    {
        return (new Cliente($this->cliente_id));
    }

    public function getLancamentos(): array
    {
        return $this->lancamentos;
    }

    public function getValorTotalLancamentos()
    {
        $valor = 0;
        //$conn = Transaction::get();

        $repository = new Repository('TSLancamentos');
        $criteria = new Criteria;
        $criteria->add(new Filter('idvendaxcontrato', '=', $this->ts_idvendaxcontrato ));

        $lancamentos = $repository->load($criteria);

        //$sql = "select abs(sum(vlroriginal)) as valor_venda from ts_lancamentos where idvendaxcontrato = " . $this->ts_idvendaxcontrato;
        //$valor = $conn->query($sql)->fetchColumn();
        
        if ($lancamentos) {
            foreach ($lancamentos as $lancamento) {
                $valor += abs($lancamento->vlroriginal);
            }
            return $valor;
        }

        return $valor;

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