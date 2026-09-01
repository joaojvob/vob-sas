# Padrões e Boas Práticas do Projeto

Para garantir a escalabilidade e a manutenibilidade deste SaaS, as IAs envolvidas devem sempre respeitar os seguintes padrões arquiteturais:

## 1. Idioma do Código

- **Backend (Classes, Variáveis, Banco de Dados, Arquivos):** EXCLUSIVAMENTE em Inglês (ex: `Store`, `Order`, `InventoryMovement`). O Laravel depende disso para sua engine de pluralização nativa.
- **Frontend (Telas Vue, Textos ao usuário, Docs no código):** Em Português.

## 2. Tipagem e Documentação

- Todo método no backend (Controllers, Actions, Traits) **deve** ter tipagem forte e explícita do PHP 8+ tanto nos argumentos quanto no retorno.
- Evitar comentários "óbvios". O PHPDoc deve ser usado para explicar regras de negócio complexas ou arrays aninhados.

## 3. Separação de Responsabilidades (SOLID)

- **Controllers:** Não devem conter regras de negócio complexas e cálculos. Apenas recebem a `Request` (de preferência via `FormRequest` para validação), despacham para uma `Action/Service` e retornam a `Response/View`.
- **Action Classes:** Devem conter a lógica de negócio do sistema (ex: Processar a compra, dar baixa no estoque, etc) garantindo o Princípio da Responsabilidade Única (SRP).
- **Tratamento de Exceções:** Evite colocar `try/catch` de forma indiscriminada. O Laravel possui um Exception Handler global. Erros já são capturados e reportados na ferramenta Opcodes Log Viewer instalada (`/log-viewer`). Apenas use `try/catch` quando houver uma tratativa de fallback específica do negócio.

## 4. Vue & Inertia.js

- **Componentização:** Não crie páginas monolíticas gigantes. Separe os elementos em pequenos componentes reutilizáveis dentro da pasta `Components/`.
- Siga a API de Composition do Vue 3 (`<script setup>`).
