# Autenticação e Planos de Assinatura (SaaS)

## Sistema de Login Flexível

O sistema original do Laravel Breeze foi refatorado.

- **Identificador de Acesso:** Em vez de e-mail, a aplicação usa um `username` exclusivo na tabela `users`.
- **Criação de Clientes (Tenants):** O Super Admin cadastra a "Conta/Empresa" (Tenant) e o "Dono da Loja" (User com role `store_owner`). Na criação, o Super Admin pode definir uma senha inicial ou deixar em branco (o sistema usará o CPF como senha temporária).
- **Redefinição Forçada de Senha:** Independentemente da senha inicial, a flag `must_change_password` (booleano na tabela `users`) é ativada por padrão para novos lojistas e também quando o Super Admin reseta a senha do cliente.
- **Fluxo do Primeiro Acesso:** Ao fazer login (com `username` e senha provisória), o middleware `ForcePasswordChange` barra o acesso ao sistema e redireciona o lojista para a rota `password.force.change`, onde ele é obrigado a cadastrar uma senha definitiva de segurança.

## Planos e Bloqueios (Middlewares)

O SaaS controla o acesso dos clientes através de Planos de Assinatura e Status.

- **Status Ativo/Inativo:** O Super Admin pode desativar um Tenant a qualquer momento. Se inativo, o Middleware `CheckTenantAccess` derruba a sessão do lojista instantaneamente com erro.
- **Planos (Trial vs Monthly):** A tabela `tenants` possui a coluna `plan` (trial, monthly) e `expires_at` (timestamp).
- **Vencimento Automático:** Ao criar um Tenant "Trial", ele ganha 7 dias. O "Mensal" ganha 30 dias. Se o `expires_at` vencer, o `CheckTenantAccess` barra o login do cliente exigindo renovação.

## Dashboard do Super Admin

A rota `/admin/dashboard` contém indicadores globais consolidados do SaaS:

- Total de Lojistas, Lojistas Ativos, Lojas Físicas Registradas.
- Listagem (tabela) dos 5 clientes recém-cadastrados para monitoramento de adoção.
