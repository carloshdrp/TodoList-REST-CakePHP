# Lista de Tarefas REST API - CakePHP

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![CakePHP](https://img.shields.io/badge/CakePHP-D33C43?style=for-the-badge&logo=cakephp&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-2b5d80.svg?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-blue.svg?style=for-the-badge)
![GitHub last commit](https://img.shields.io/github/last-commit/carloshdrp/TodoList-REST-CakePHP?style=for-the-badge)
![GitHub issues](https://img.shields.io/github/issues/carloshdrp/TodoList-REST-CakePHP?style=for-the-badge)
![GitHub stars](https://img.shields.io/github/stars/carloshdrp/TodoList-REST-CakePHP?style=for-the-badge)

Uma API REST completa para gerenciamento de tarefas (To-Do List) construída com CakePHP 4.6, incluindo autenticação, rate limiting e paginação.

## 🚀 Funcionalidades

- ✅ **CRUD completo** de tarefas
- 🔐 **Autenticação JWT** com refresh tokens
- 📊 **Paginação** com parâmetros customizáveis
- 🛡️ **Rate Limiting** para proteção contra spam
- 🐳 **Docker** para desenvolvimento
- 🔄 **API RESTful** seguindo padrões HTTP

## 📋 Endpoints da API

### Tarefas

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/tasks` | Lista todas as tarefas com paginação |
| `POST` | `/tasks` | Cria uma nova tarefa |
| `POST` | `/tasks/{id}` | Atualiza uma tarefa existente |
| `DELETE` | `/tasks/{id}` | Remove uma tarefa |

### Parâmetros de Paginação

- `page`: Número da página (padrão: 1)
- `limit`: Itens por página (padrão: 10)

**Exemplo de requisição:**
```curl
GET api/v1/tasks?page=1&limit=5
```

**Exemplo de resposta:**
```json
{
    "success": true,
    "data": [
        {
            "id": 2,
            "title": "Segunda Tarefa",
            "description": "Descrição da segunda tarefa"
        },
        {
            "id": 1,
            "title": "Primeira Tarefa",
            "description": "Descrição da tarefa"
        }
    ],
    "page": 1,
    "limit": 5,
    "total": 2
}
```

## 🛠️ Tecnologias Utilizadas

- **CakePHP 4.6** - Framework PHP
- **PHP 8.2** - Linguagem de programação
- **MySQL** - Banco de dados
- **Docker** - Containerização
- **nginx** - Servidor HTTP
- **APCu** - Cache para rate limiting
- **Lcobucci\JWT** - Biblioteca para JWT
- **Composer** - Gerenciador de dependências

## 🔒 Rate Limiting

A API implementa rate limiting para proteger contra abuso:

- Limite padrão: 50 requisições por minuto
- Identificação: Por token de autorização ou IP
- Resposta quando excedido: HTTP 429

**Exemplo de resposta com limite excedido:**
```json
{
"success": false,
"message": "Rate Limit excedido. Tente novamente mais tarde."
}
```

## 🤝 Contribuindo
1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 🔗 Links Úteis
- [CakePHP Documentation](https://book.cakephp.org/4/en/index.html)
- [REST API Best Practices](https://restfulapi.net/)
- [Docker Documentation](https://docs.docker.com/)

⭐ Se este projeto te ajudou, considere dar uma estrela no repositório!
