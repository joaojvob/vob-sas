# Regra de Ouro do Isolamento (Tenant Scope)

Todas as models de domínio devem garantir isolamento de dados.

Para isso:

1. Devem utilizar uma trait chamada `BelongsToTenant`.
2. Esta trait aplica um **Global Scope** filtrando automaticamente pelo `tenant_id` atual. O `tenant_id` atual é resolvido via middleware, usando o usuário autenticado.
3. A trait preenche automaticamente o `tenant_id` no evento `creating` do ciclo de vida da model, garantindo que nenhum registro seja criado sem o vínculo com o Tenant.
