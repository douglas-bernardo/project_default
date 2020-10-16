SELECT 
	o.nome as origem,
    count(n.id) as total
FROM negociacao n
	left join origem o on n.origem_id = o.id
	group by 1