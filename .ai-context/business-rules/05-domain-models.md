# Domínio de Negócios e Modelagem

O sistema foi modelado para atender pequenos negócios (como lanchonetes, bares, espetinhos e sacolões). O termo "Store" representa o Estabelecimento/Ponto de Venda.

## Estrutura e Entidades Principais

1. **Tenant (Lojista):** A assinatura do cliente do SaaS. 
2. **User (Funcionários/Donos):** Usuários do estabelecimento. 
   - *Regra:* Possuem chave única composta (`tenant_id`, `cpf`) e (`tenant_id`, `email`).
   - *Regra:* Possuem uma flag `must_change_password` forçando a troca de senha no primeiro login via Middleware `ForcePasswordChange`.
3. **Store (Estabelecimento):** Representa o ponto físico.
   - *Regra:* Possui flag `accepts_qr_orders` para determinar se o cardápio QR Code aceita pedidos ou se é apenas leitura.
4. **Category & Product:** O catálogo de produtos. Produtos possuem `image_url` e `description` visando a exibição no cardápio digital.
5. **InventoryMovement:** Registra as movimentações (entrada, saída, ajuste) vinculando loja, produto e usuário responsável.
6. **Order & OrderItem (Pedidos):** Controla os pedidos realizados, sejam no balcão ou via QR Code. O `Order` possui status (`pending`, `preparing`, `ready`, `completed`, `cancelled`) e a identificação de mesa/cliente (`table_number_or_name`).
