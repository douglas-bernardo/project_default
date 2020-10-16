SELECT 
	st.nome,
	sum(lancamentos.valor) as valor_solicitado
	FROM negociacao n
	left join contrato c on n.contrato_id = c.id
	left join situacao st on n.situacao_id = st.id
	left join 
(select 
	sum(abs(vlroriginal)) as valor,
	idvendaxcontrato
from 
	ts_lancamentos
	group by idvendaxcontrato) lancamentos 
    on c.ts_idvendaxcontrato = lancamentos.idvendaxcontrato
    group by 1