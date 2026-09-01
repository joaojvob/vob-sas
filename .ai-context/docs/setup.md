# Guia de Configuração e Instalação (VOB SAS)

Este documento descreve como configurar e rodar o projeto localmente via Docker (Sail) e Vite.

## 1. Pré-requisitos
- Docker e Docker Compose instalados.
- PHP e Composer instalados no Host (para a primeira instalação das dependências, caso não use o container efêmero).
- Node.js e NPM instalados no Host (recomendado para rodar o Vite em desenvolvimento).

## 2. Passo a Passo Inicial

1. **Clonar o repositório:**
   ```bash
   git clone <url-do-repo> vob-sas
   cd vob-sas
   ```

2. **Copiar o arquivo de ambiente:**
   ```bash
   cp .env.example .env
   ```

3. **Instalar Dependências do PHP:**
   Se não tiver PHP local, use um container efêmero do Composer:
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php83-composer:latest \
       composer install --ignore-platform-reqs
   ```

4. **Subir os Containers (Sail):**
   O projeto utiliza o Laravel Sail estendido. O serviço `app` usa a imagem `sail-8.3/app`.
   Portas expostas: Nginx na `8081` (no host) / Redis na `6380`. (Traefik usa portas nativas).
   ```bash
   ./vendor/bin/sail up -d
   # ou usando o alias se configurado
   sail up -d
   ```

5. **Gerar a chave da aplicação e rodar migrations:**
   ```bash
   sail artisan key:generate
   sail artisan migrate:fresh --seed
   ```
   > **Atenção:** O comando de `--seed` criará o Super Admin padrão e limpará todo o banco.

6. **Instalar Dependências e Compilar o Frontend:**
   O frontend utiliza Vue.js + Inertia + Tailwind CSS construídos via Vite.
   No ambiente Docker, usamos:
   ```bash
   docker exec -it vob_sas_app npm install
   docker exec -it vob_sas_app npm run build
   ```
   *(Em modo de desenvolvimento, você pode rodar `npm run dev` no Host, mas lembre-se de configurar o HMR no `vite.config.js` caso as portas difiram).*

## 3. Acessando a Aplicação
- URL Base: `http://localhost:8081` (Porta alterada para evitar conflito com o Traefik).
- URL do Super Admin: `http://localhost:8081/admin/login`
- Credenciais Padrão (Super Admin):
  - E-mail: `superadmin@vobsas.com`
  - Senha: `password`

## 4. Dicas de Desenvolvimento
- Qualquer mudança em arquivos `.vue` ou `app.js` necessita de um build do Vite: `docker exec vob_sas_app npm run build`.
- Para debugar erros ou ver logs, você pode acessar `http://localhost:8081/admin/log-viewer` (Autenticado como admin) ou `sail logs app`.
