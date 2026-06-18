# UpFlow - API de Regulação de Leitos

API REST desenvolvida em Laravel para gerenciamento de leitos hospitalares, contemplando o controle de internações, altas hospitalares, transferências entre leitos e consultas de disponibilidade.

A solução foi desenvolvida como parte de um desafio técnico, adotando uma arquitetura baseada em Service Layer para centralização das regras de negócio, utilização do Eloquent ORM para persistência de dados e documentação interativa por meio do Swagger/OpenAPI.

---

# Tecnologias Utilizadas

* PHP 8.2
* Laravel 12
* MySQL
* SQLite (desenvolvimento local)
* Eloquent ORM
* REST API
* L5-Swagger (OpenAPI 3.0)
* Railway
* GitHub

---

# Funcionalidades Implementadas

## Tipos de Leito

* Cadastro de tipos de leito
* Consulta de tipos de leito
* Atualização de tipos de leito
* Exclusão de tipos de leito

## Leitos

* Cadastro de leitos
* Consulta de leitos
* Atualização de leitos
* Exclusão de leitos
* Consulta do status de ocupação de um leito
* Listagem de todos os leitos com seus respectivos status de ocupação

## Pacientes

* Cadastro de pacientes
* Consulta de pacientes
* Atualização de pacientes
* Exclusão de pacientes
* Consulta do leito ocupado por um paciente a partir do CPF

## Internações

* Registro de internações
* Consulta de internações
* Registro de alta hospitalar
* Transferência de pacientes entre leitos

---

# Requisitos do Desafio Atendidos

O projeto contempla todos os requisitos funcionais solicitados no desafio técnico:

* Inclusão de paciente em um leito;
* Desocupação de leito por meio de alta hospitalar;
* Transferência de paciente entre leitos;
* Consulta do leito ocupado por um paciente a partir do CPF;
* Consulta do status de ocupação de um leito;
* Listagem de todos os leitos com seus respectivos status de ocupação.

Além dos requisitos solicitados, foram implementadas as seguintes validações de negócio:

* Um paciente não pode possuir mais de uma internação ativa simultaneamente;
* Um leito não pode possuir mais de um paciente simultaneamente;
* Uma internação não pode receber alta mais de uma vez;
* Não é permitida a transferência de uma internação encerrada;
* Não é permitida a transferência para o mesmo leito.

---

# Regras de Negócio

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

## Regra 4

Uma internação encerrada não pode ser transferida.

Exemplo:

```json
{
  "message": "Nao e possivel transferir uma internacao finalizada."
}
```

---

## Regra 5

Não é permitida a transferência de um paciente para o mesmo leito.

Exemplo:

```json
{
  "message": "Paciente ja esta neste leito."
}
```

---

# Arquitetura da Solução

## Models

* TipoLeito
* Leito
* Paciente
* Internacao

## Relacionamentos

### TipoLeito

* hasMany(Leito)

### Leito

* belongsTo(TipoLeito)
* hasMany(Internacao)

### Paciente

* hasMany(Internacao)

### Internacao

* belongsTo(Paciente)
* belongsTo(Leito)

---

# Service Layer

Toda a lógica de negócio foi centralizada na seguinte classe:

```text
app/Services/RegulacaoLeitosService.php
```

Responsabilidades:

* Validação de internações;
* Controle de ocupação de leitos;
* Controle de altas hospitalares;
* Transferência de pacientes entre leitos;
* Consulta de leito por CPF;
* Consulta de status de leitos.

---

# Estrutura do Projeto

* CRUD de Tipos de Leito;
* CRUD de Leitos;
* CRUD de Pacientes;
* Controle de Internações;
* Controle de Altas;
* Transferência de Pacientes entre Leitos;
* Consulta de Leito por CPF;
* Consulta de Status de Leitos;
* Listagem de Ocupação dos Leitos;
* Seeders para dados iniciais;
* Documentação Swagger/OpenAPI;
* Service Layer para centralização das regras de negócio.

---

# Guia de Execução

## 1. Clonar o Repositório

```bash
git clone https://github.com/zemauromm/upflow-regulacao-leitos-api.git

cd upflow-regulacao-leitos-api
```

---

## 2. Instalar as Dependências

```bash
composer install
```

---

## 3. Criar o Arquivo de Ambiente

### Windows

```powershell
copy .env.example .env
```

### Linux

```bash
cp .env.example .env
```

---

## 4. Gerar a Chave da Aplicação

```bash
php artisan key:generate
```

---

## 5. Configurar o Banco de Dados

### SQLite

Windows:

```powershell
type nul > database/database.sqlite
```

Linux:

```bash
touch database/database.sqlite
```

Ou configure a aplicação para utilização de MySQL por meio do arquivo `.env`.

---

## 6. Executar Migrations e Seeders

```bash
php artisan migrate:fresh --seed
```

---

## 7. Gerar a Documentação Swagger

```bash
php artisan l5-swagger:generate
```

---

## 8. Iniciar a Aplicação

```bash
php artisan serve
```

A aplicação ficará disponível em:

```text
http://127.0.0.1:8000
```

---

# Documentação da API

Após iniciar a aplicação:

```text
http://127.0.0.1:8000/api/documentation
```

---

# Dados de Exemplo (Seeders)

## Tipos de Leito

| ID | Descrição  |
| -- | ---------- |
| 1  | UTI        |
| 2  | Enfermaria |
| 3  | Observacao |

## Leitos

| ID | Numero | Tipo       |
| -- | ------ | ---------- |
| 1  | UTI-01 | UTI        |
| 2  | UTI-02 | UTI        |
| 3  | ENF-01 | Enfermaria |

## Pacientes

| ID | Nome           |
| -- | -------------- |
| 1  | Joao da Silva  |
| 2  | Maria Souza    |
| 3  | Carlos Pereira |

---

# Exemplos de Utilização da API

## Criar uma Internacao

```json
{
  "paciente_id": 1,
  "leito_id": 1,
  "data_internacao": "2026-06-18 03:45:00"
}
```

---

## Registrar Alta

```http
PATCH /api/internacoes/{id}/alta
```

---

## Transferir Paciente

```http
PATCH /api/internacoes/{id}/transferir
```

Body:

```json
{
  "leito_id": 2
}
```

---

## Buscar Leito por CPF

```http
GET /api/pacientes/cpf/12345678901/leito
```

---

## Consultar Status de um Leito

```http
GET /api/leitos/1/status
```

---

## Consultar Todos os Leitos com Status

```http
GET /api/leitos-status
```

---

# Endpoints Disponíveis

## Tipos de Leito

```text
GET     /api/tipos-leito
POST    /api/tipos-leito
GET     /api/tipos-leito/{id}
PUT     /api/tipos-leito/{id}
DELETE  /api/tipos-leito/{id}
```

## Leitos

```text
GET     /api/leitos
POST    /api/leitos
GET     /api/leitos/{id}
PUT     /api/leitos/{id}
DELETE  /api/leitos/{id}
GET     /api/leitos/{id}/status
GET     /api/leitos-status
```

## Pacientes

```text
GET     /api/pacientes
POST    /api/pacientes
GET     /api/pacientes/{id}
PUT     /api/pacientes/{id}
DELETE  /api/pacientes/{id}
GET     /api/pacientes/cpf/{cpf}/leito
```

## Internacoes

```text
GET     /api/internacoes
POST    /api/internacoes
GET     /api/internacoes/{id}
PUT     /api/internacoes/{id}
DELETE  /api/internacoes/{id}
PATCH   /api/internacoes/{id}/alta
PATCH   /api/internacoes/{id}/transferir
```

---

# Deploy

Aplicação publicada em:

https://upflow-regulacao-leitos-api-production.up.railway.app

Documentação Swagger:

https://upflow-regulacao-leitos-api-production.up.railway.app/api/documentation

Repositório:

https://github.com/zemauromm/upflow-regulacao-leitos-api

---

# Autor

**Zémauro Machado**

Desenvolvedor Full Stack

Especialidades:

* PHP
* Laravel
* Java
* Spring Boot
* Angular
* Vue/Nuxt
* Docker
* MySQL
* Oracle
* APIs REST

GitHub:

https://github.com/zemauromm
