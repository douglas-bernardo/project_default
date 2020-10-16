create view vw_cont_revertidos as
select
	rev.negociacao_id,
	rev.novocontrato_id,
	p.nomeprojeto,
	p.numeroprojeto,
	c.numero,
	c.valor_venda,
	c.data_venda
from reversao rev 
	left join contrato c on rev.novocontrato_id = c.id
	left join projeto p on c.projeto_id = p.id