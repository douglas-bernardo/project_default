-- CREATE OR REPLACE VIEW vw_analitico AS
select 
	n.id as id_negociacao,
    u.id as id_negociadora,
    u.ts_usuario_id as ts_id_negociadora,
    u.nome as negociadora,
    o.numero_ocorrencia,
    DATE_FORMAT( o.dtocorrencia , '%Y') as ano_sol,
    DATE_FORMAT( o.dtocorrencia , '%b %Y') as ciclo_ini,
    DATE_FORMAT( o.dtocorrencia , '%c') as ciclo_ini_num,
	date(o.dtocorrencia) as data_ocorrencia,
    tp.id as id_tipo_solicitacao,
    tp.nome as tipo_solicitacao,
    m.descricao as motivo,
    cl.nome as cliente,
    p.numeroprojeto,
    c.numero as contrato,
    p.nomeprojeto as produto,
    c.data_venda,
    if(st.id = 6, ret.valor_antigo, ifnull( l.valor, o.valor_venda )) as valor_venda,
    st.id as id_situacao,
    st.nome as situacao,
    n.data_finalizacao,
    DATE_FORMAT( n.data_finalizacao , '%Y') as ano_fin,
    n.multa,
    n.reembolso,
    n.taxas_extras,
    -- c.data_debito_pontos
    c_rev.numeroprojeto as projeto_novo,
    c_rev.numero as contrato_novo,
    c_rev.valor_venda as valor_revertido,
    if(st.id = 6, ret.valor_novo, null) as valor_retido,
    (ret.valor_novo - ret.valor_antigo) as dif_retencao,
    n.valor_primeira_parcela as caixa,
    if(st.id = 7, ifnull( l.valor, o.valor_venda ) - c_rev.valor_venda - (n.multa+n.taxas_extras), if(st.id = 2, ifnull( l.valor, o.valor_venda ) - (n.multa+n.taxas_extras), 0)) as perda_financeira,
    if(st.id = 6, ret.valor_novo, if(st.id = 7, c_rev.valor_venda, 0)) as faturamento
from negociacao n 
     left join ocorrencia o on n.ocorrencia_id = o.id
     left join usuario u on n.usuario_id = u.id
     left join tipo_solicitacao tp on n.tipo_solicitacao_id = tp.id
     left join motivo m on o.idmotivots = m.idmotivots
     left join contrato c on n.contrato_id = c.id
     left join projeto p on c.projeto_id = p.id
     left join cliente cl on c.cliente_id = cl.id
     left join situacao st on n.situacao_id = st.id
     left join retencao ret on n.id = ret.negociacao_id
     left join vw_lancamentos l on c.ts_idvendaxcontrato = l.idvendaxcontrato
	 left join vw_cont_revertidos c_rev on n.id = c_rev.negociacao_id