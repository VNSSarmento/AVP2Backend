# API de Transações

API REST desenvolvida em PHP com Slim Framework para gerenciamento de transações financeiras e cálculo de estatísticas.

## Requisitos

- PHP 7.4+ ou 8.0+
- MySQL 5.7+
- Composer
- Extensões PHP: PDO, JSON

## Instalação

1. Clone o repositório: 
```bash
git clone <seu-repositorio>
cd transacoes-api
```

2. Instale as dependências:
```bash
composer install
```

3. Configure o banco de dados:
   - Crie um banco MySQL chamado `transacoes_api`
   - Ajuste as credenciais em `config/database.php` se necessário

4. Execute a aplicação:
```bash
php -S localhost:8080 index.php
```

## Estrutura do Projeto

```
/
├── index.php                 # Arquivo principal
├── composer.json            # Dependências
├── config/
│   └── database.php        # Configuração do banco
├── src/
│   ├── controllers/        # Controllers da API
│   ├── models/            # Modelos de dados
│   ├── services/          # Lógica de negócio
│   └── validators/        # Validadores
└── README.md
```

## Endpoints da API

### POST /transacao
Cria uma nova transação.

**Corpo da requisição:**
```json
{
    "id": "123e4567-e89b-12d3-a456-426614174000",
    "valor": 100.50,
    "dataHora": "2023-12-01T10:30:00Z"
}
```

**Respostas:**
- `201 Created`: Transação criada com sucesso
- `400 Bad Request`: JSON inválido
- `422 Unprocessable Entity`: Dados inválidos

### GET /transacao/{id}
Busca uma transação pelo ID.

**Resposta de sucesso (200):**
```json
{
    "id": "123e4567-e89b-12d3-a456-426614174000",
    "valor": 100.50,
    "dataHora": "2023-12-01T10:30:00Z"
}
```

**Respostas:**
- `200 OK`: Transação encontrada
- `404 Not Found`: Transação não encontrada

### DELETE /transacao
Remove todas as transações.

**Respostas:**
- `200 OK`: Todas as transações removidas

### DELETE /transacao/{id}
Remove uma transação específica.

**Respostas:**
- `200 OK`: Transação removida
- `404 Not Found`: Transação não encontrada

### GET /estatistica
Calcula estatísticas das transações dos últimos 60 segundos.

**Resposta de sucesso (200):**
```json
{
    "count": 5,
    "sum": 500.25,
    "avg": 100.05,
    "min": 50.00,
    "max": 200.00
}
```

## Regras de Validação

### Transações
- **ID**: Obrigatório, deve ser um UUID válido e único
- **Valor**: Obrigatório, deve ser numérico e >= 0
- **DataHora**: Obrigatória, deve estar no formato ISO 8601 e não pode ser no futuro

### Estatísticas
- Calculadas apenas para transações dos últimos 60 segundos
- Quando não há transações no período, todos os valores retornam 0

## Exemplos de Uso

### Criar transação:
```bash
curl -X POST http://localhost:8080/transacao \
  -H "Content-Type: application/json" \
  -d '{
    "id": "123e4567-e89b-12d3-a456-426614174000",
    "valor": 100.50,
    "dataHora": "2023-12-01T10:30:00Z"
  }'
```

### Buscar transação:
```bash
curl http://localhost:8080/transacao/123e4567-e89b-12d3-a456-426614174000
```

### Obter estatísticas:
```bash
curl http://localhost:8080/estatistica
```

### Limpar todas as transações:
```bash
curl -X DELETE http://localhost:8080/transacao
```

## Tecnologias Utilizadas

- PHP 8.0+
- Slim Framework 4.x
- MySQL
- PDO para acesso ao banco
- Design Patterns: Singleton (Database), MVC

## Arquitetura

A API segue os princípios de Clean Architecture:

- **Controllers**: Gerenciam as requisições HTTP
- **Services**: Contêm a lógica de negócio
- **Models**: Representam os dados e acesso ao banco
- **Validators**: Validam os dados de entrada
- **Database**: Configuração e conexão com MySQL