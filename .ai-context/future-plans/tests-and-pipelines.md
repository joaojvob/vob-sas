# Planos Futuros: Testes e Pipelines (CI/CD)

Este documento registra as intenções e o planejamento para a implementação de testes automatizados e o fluxo de pipelines para o SaaS, devendo ser priorizado após a conclusão do *Code Review* da estrutura base.

## 1. Testes Automatizados (Pest / PHPUnit)

Toda a lógica de autenticação e fluxos cruciais criados até o momento deverão ser testados para garantir que futuras features não quebrem as regras de negócio de Segurança e Isolamento.

### Suíte de Testes a Implementar:
- **Testes de Integração e Acesso (Middlewares):**
  - Garantir que o Middleware `CheckTenantAccess` bloqueia Tenants com status `inactive`.
  - Garantir que o Middleware bloqueia Tenants com `expires_at` vencido.
  - Garantir que o `ForcePasswordChange` redireciona corretamente se a flag estiver `true`.
- **Testes de Criação de Tenant e Admin:**
  - Validar a Action `CreateTenantAndOwnerAction` (criação correta de tenant, usuário dono, associação de role `store_owner` no time correto, e hash de senha).
  - Testar unicidade do campo `username` e validações de CPF.
- **Testes de Rota e Autorização:**
  - Certificar que lojistas (auth:web) não conseguem acessar rotas de Super Admin (auth:admin).

## 2. Impactos na Pipeline (CI/CD)

Ao subir este projeto para um repositório remoto ou ambiente de produção (Github Actions / GitLab CI), devemos considerar as seguintes etapas:

### Estágio de Build (Frontend & Backend)
1. Instalação limpa via `composer install --no-dev --optimize-autoloader`.
2. Instalação Node via `npm ci` e compilação de assets estáticos via `npm run build` (Inertia/Vite).
3. Cache agressivo do Laravel (`config:cache`, `route:cache`, `view:cache`).

### Estágio de Testes
A pipeline deverá obrigatoriamente rodar os testes descritos acima. 
- O job do banco de dados na pipeline precisa ser PostgreSQL.
- Se os testes falharem (ex: alguém tirou a validação de plano expirado sem querer), o merge request/deploy é abortado.

### Estágio de Deploy
Considerações para a infraestrutura de produção:
- **Migrações:** Executar `php artisan migrate --force` em produção de forma isolada.
- **Isolamento de Cache:** Como é um SaaS com arquitetura Multi-Tenant baseada em Scopes (Team ID do Spatie + Global Scopes que serão implementados futuramente), o Redis deve estar 100% isolado ou as chaves tagueadas por Tenant.
- **Limpeza do Vite:** Não esquecer de publicar o `manifest.json` do Vite gerado no build.
