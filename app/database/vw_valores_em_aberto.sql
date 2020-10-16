-- Definição de view valores em aberto apenas para solicitações pós 7 dias e sol. de negociação 
-- ---------------------------------------------------------------------------------------------
-- CREATE OR REPLACE VIEW vw_valores_em_aberto AS
SELECT 
    u.id AS id_negociadora,
    tp.id AS id_tipo_solicitacao,
    st.id AS id_situacao,
    DATE_FORMAT( o.dtocorrencia , '%Y') as ano_sol,
	sum(IF(st.id = 6, ret.valor_antigo, IFNULL( l.valor, o.valor_venda ))) AS valor_em_aberto
FROM negociacao n 
     LEFT JOIN ocorrencia o ON n.ocorrencia_id = o.id
     LEFT JOIN usuario u ON n.usuario_id = u.id
     LEFT JOIN tipo_solicitacao tp ON n.tipo_solicitacao_id = tp.id
     LEFT JOIN contrato c ON n.contrato_id = c.id
     LEFT JOIN situacao st ON n.situacao_id = st.id
     LEFT JOIN retencao ret ON n.id = ret.negociacao_id
     LEFT JOIN vw_lancamentos l ON c.ts_idvendaxcontrato = l.idvendaxcontrato
WHERE st.id = 1 AND tp.id IN ( 2 , 4 ) GROUP BY 1