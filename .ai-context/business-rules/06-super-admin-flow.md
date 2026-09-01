# Painel do Super Admin e Setup Inicial

Toda a área de gerenciamento global do SaaS (onde criamos clientes/tenants) está estruturada da seguinte forma:

## 1. Isolamento Front/Back

- **Rotas:** Ficam agrupadas em `routes/web.php` sob o prefixo `/admin` usando o guard `auth:admin`.
- **Controllers:** Localizados em `app/Http/Controllers/Admin/*`.
- **Views (Vue/Inertia):** Localizadas em `resources/js/Pages/Admin/*`, utilizando um layout exclusivo `AdminLayout.vue` para garantir que o Super Admin não seja deslogado ou interceptado pelas regras do Tenant.

## 2. Boas Práticas (Action Classes)

- O fluxo de criação de um Tenant é complexo, pois exige criar a assinatura (`Tenant`), o usuário inicial, e atrelar as permissões (Spatie Role `store_owner` com `team_id`).
- Essa lógica pesada **NÃO** fica no Controller.
- Foi adotado o padrão Action Classes (Serviços) através de `App\Actions\CreateTenantAndOwnerAction`, encapsulando toda a regra de negócio em um bloco de transação (`DB::transaction`).

## 3. Senha e Acesso de Clientes

- Ao criar um Lojista, o Super Admin define o Nome, Email e CPF do dono.
- A senha inicial do dono é extraída dos números do seu CPF.
- O dono obrigatoriamente terá a flag `must_change_password` setada para `true`.
- O middleware `ForcePasswordChange` interceptará o acesso assim que ele fizer login, forçando a troca de senha.
