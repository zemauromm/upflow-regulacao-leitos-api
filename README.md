# UpFlow - API de Regulação de Leitos

API REST desenvolvida em Laravel para gerenciamento de leitos hospitalares, controle de internações, altas hospitalares e disponibilidade de leitos.

Desafio técnico desenvolvido utilizando PHP, Laravel, SQLite, Eloquent ORM e documentação interativa com Swagger.

---

# Tecnologias Utilizadas

- PHP 8.2
- Laravel 12
- SQLite
- Eloquent ORM
- REST API
- L5-Swagger (OpenAPI 3.0)

---

# Funcionalidades

## Tipos de Leito

- Cadastrar tipo de leito
- Consultar tipos de leito
- Atualizar tipo de leito
- Excluir tipo de leito

## Leitos

- Cadastrar leitos
- Consultar leitos
- Atualizar leitos
- Excluir leitos

## Pacientes

- Cadastrar pacientes
- Consultar pacientes
- Atualizar pacientes
- Excluir pacientes

## Internações

- Registrar internação
- Consultar internações
- Registrar alta hospitalar

---

# Regras de Negócio Implementadas

## Regra 1

Um leito não pode possuir mais de uma internação ativa.

Exemplo:

```json
{
  "message": "Leito ja esta ocupado."
}
```

---

## Regra 2

Um paciente não pode possuir mais de uma internação ativa.

Exemplo:

```json
{
  "message": "Paciente ja possui internacao ativa."
}
```

---

## Regra 3

Uma internação não pode receber alta mais de uma vez.

Exemplo:

```json
{
  "message": "Internacao ja possui alta registrada."
}
```

---

# Estrutura do Projeto

- CRUD de Tipos de Leito
- CRUD de Leitos
- CRUD de Pacientes
- Controle de Internações
- Controle de Altas
- Seeders para dados iniciais
- Documentação Swagger/OpenAPI

---

# Executando o Projeto

## 1 - Clonar o repositório

```bash
git clone https://github.com/zemauromm/upflow-regulacao-leitos-api.git
cd upflow-regulacao-leitos-api
```

---

## 2 - Instalar dependências

```bash
composer install
```

---

## 3 - Criar arquivo de ambiente

### Windows

```powershell
copy .env.example .env
```

### Linux

```bash
cp .env.example .env
```

---

## 4 - Gerar a chave da aplicação

```bash
php artisan key:generate
```

---

## 5 - Criar banco SQLite

### Windows

```powershell
type nul > database/database.sqlite
```

### Linux

```bash
touch database/database.sqlite
```

---

## 6 - Executar migrations e seeders

```bash
php artisan migrate:fresh --seed
```

---

## 7 - Gerar documentação Swagger

```bash
php artisan l5-swagger:generate
```

---

## 8 - Iniciar aplicação

```bash
php artisan serve
```

A API ficará disponível em:

```text
http://127.0.0.1:8000
```

---

# Documentação Swagger

Após iniciar a aplicação, acesse:

```text
http://127.0.0.1:8000/api/documentation
```

---

# Dados Iniciais (Seeders)

## Tipos de Leito

| ID | Descrição |
|----|------------|
| 1 | UTI |
| 2 | Enfermaria |
| 3 | Observação |

---

## Leitos

| ID | Número | Tipo |
|----|---------|-------|
| 1 | UTI-01 | UTI |
| 2 | UTI-02 | UTI |
| 3 | ENF-01 | Enfermaria |

---

## Pacientes

| ID | Nome |
|----|-------|
| 1 | João da Silva |
| 2 | Maria Souza |
| 3 | Carlos Pereira |

---

# Exemplos de Uso

## Criar uma Internação

```json
{
  "paciente_id": 1,
  "leito_id": 1,
  "data_internacao": "2026-06-17 22:00:00"
}
```

---

## Registrar Alta

```http
PATCH /api/internacoes/{id}/alta
```

---

# Endpoints Principais

## Tipos de Leito

```
GET     /api/tipos-leito
POST    /api/tipos-leito
GET     /api/tipos-leito/{id}
PUT     /api/tipos-leito/{id}
DELETE  /api/tipos-leito/{id}
```

## Leitos

```
GET     /api/leitos
POST    /api/leitos
GET     /api/leitos/{id}
PUT     /api/leitos/{id}
DELETE  /api/leitos/{id}
```

## Pacientes

```
GET     /api/pacientes
POST    /api/pacientes
GET     /api/pacientes/{id}
PUT     /api/pacientes/{id}
DELETE  /api/pacientes/{id}
```

## Internações

```
GET     /api/internacoes
POST    /api/internacoes
GET     /api/internacoes/{id}
PUT     /api/internacoes/{id}
DELETE  /api/internacoes/{id}
PATCH   /api/internacoes/{id}/alta
```

---

# Autor

**Zémauro Machado**

Desenvolvedor Full Stack

- PHP
- Laravel
- Java
- Spring Boot
- Angular
- Vue/Nuxt
- Docker
- MySQL
- Oracle
- APIs REST

GitHub:

https://github.com/zemauromm
