# Lista de Tarefas Simples (PHP + MySQL)

Projeto simples de **CRUD de tarefas** feito com **PHP, MySQL e Bootstrap**.
Permite criar, editar, marcar como concluída e apagar tarefas.

## Funcionalidades

* Adicionar nova tarefa
* Editar tarefa
* Marcar tarefa como concluída
* Apagar tarefa
* Interface simples usando Bootstrap

## Requisitos

* PHP 7+
* MySQL
* Servidor local (XAMPP, Laragon, WAMP ou `php -S`)

## Banco de Dados

Execute o SQL abaixo para criar o banco e a tabela:

```sql
CREATE DATABASE tarefas;

USE tarefas;

CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    concluido TINYINT(1) DEFAULT 0
);
```

## Como executar

1. Crie o banco de dados usando o SQL acima.
2. Coloque o arquivo `index.php` no servidor local.
3. Ajuste as credenciais do banco se necessário:

```php
$pdo = new PDO("mysql:host=localhost;dbname=tarefas", "root", "");
```

4. Acesse no navegador:

```
http://localhost/seu-projeto
```

## Estrutura

```
/projeto
 ├─ index.php
 ├─ style.css
 └─ README.md
```
