# Autenticação e RBAC (Role-Based Access Control)

Existem dois guards isolados para autenticação:

- **admin:** Direcionado para a tabela `super_admins` (gestores do SaaS).
- **web:** Direcionado para a tabela `users` (clientes/usuários dos Tenants).

## Gestão de Permissões

- O projeto utiliza o pacote `spatie/laravel-permission`.
- A feature `teams` está **ativada**, onde o `team_id` corresponde ao `tenant_id`.

## Regras de Usuário

- O campo único (unique) de usuários deve ser uma chave composta: `(tenant_id, email)`, permitindo que o mesmo email exista em Tenants diferentes.
