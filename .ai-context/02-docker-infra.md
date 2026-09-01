# Infraestrutura Docker

A aplicação roda inteiramente em contêineres Docker utilizando `docker-compose`.

## Serviços

1. **app:** Container da aplicação principal
   - **Base:** PHP-FPM 8.3+
   - **Adicional:** Node.js (necessário para compilar os assets do Vite e rodar o HMR na porta 5173).

2. **web:** Servidor web
   - **Base:** Nginx

3. **db:** Banco de Dados
   - **Base:** PostgreSQL (com volume persistente para os dados)

4. **redis:** Cache e Filas
   - **Base:** Redis Alpine
