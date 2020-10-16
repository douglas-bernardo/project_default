create view vw_lancamentos as
select 
	sum(abs(vlroriginal)) as valor,
	idvendaxcontrato
from 
	ts_lancamentos
	group by idvendaxcontrato