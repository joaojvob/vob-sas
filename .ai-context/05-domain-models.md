# Domínio de Negócios e Modelagem

A estrutura básica de negócios relacionada a estoque em lanchonetes e comércio possui os seguintes relacionamentos principais:

- **Tenant** (1:N) **Store** (Lojas/Filiais do Tenant)
- **Category** (1:N) **Product** (Categorias e seus Produtos)
- **Product** (1:N) **InventoryMovement** (Movimentações de entrada/saída de cada produto no estoque)
