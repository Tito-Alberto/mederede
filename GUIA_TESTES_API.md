# 🧪 Guia de Testes da API

## Pré-requisitos

- Laravel Sanctum configurado para autenticação via token
- Database com dados de teste (execute `php artisan migrate:fresh --seed`)
- Insomnia, Postman ou ferramenta similar para testar APIs

---

## 🔑 Autenticação

### 1. Obter Token de Autenticação

Antes de testar as rotas protegidas, precisa de um token. Laravel Sanctum fornece isso via:

```bash
POST /api/login
Content-Type: application/json

{
  "email": "admin@mederede.com",
  "password": "password"
}
```

**Resposta (exemplo):**
```json
{
  "access_token": "YOUR_TOKEN_HERE",
  "token_type": "Bearer"
}
```

Usar em todos os requests protegidos:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## 📋 Testes de Rotas Públicas

### Listar Notificações (Sem autenticação)
```http
GET /api/notificacaos
Accept: application/json
```

**Resposta:**
```json
{
  "data": [],
  "links": { ... },
  "meta": { ... }
}
```

### Visualizar Notificação Específica
```http
GET /api/notificacaos/1
Accept: application/json
```

---

## 🔐 Testes de Rotas Protegidas

### Perfil do Utilizador Autenticado
```http
GET /api/user
Authorization: Bearer TOKEN_HERE
Accept: application/json
```

**Resposta:**
```json
{
  "id": 1,
  "name": "Admin",
  "email": "admin@mederede.com",
  "role": "admin",
  "email_verified_at": null,
  "created_at": "2026-01-21T06:05:27.000000Z",
  "updated_at": "2026-01-21T06:05:27.000000Z"
}
```

---

## 📝 Testes CRUD de Casos

### Listar Casos (Profissional/Admin)
```http
GET /api/casos
Authorization: Bearer TOKEN_HERE
Accept: application/json
```

**Parâmetros opcionais de filtro:**
```
GET /api/casos?doenca_id=1&status=confirmado&localizacao=Lisboa&page=1&per_page=15
```

**Resposta:**
```json
{
  "data": [
    {
      "id": 1,
      "paciente_nome": "João Silva",
      "idade": 35,
      "localizacao": "Lisboa",
      "data_inicio": "2026-01-20",
      "sintomas": "Febre alta, dores no corpo",
      "latitude": "38.72230000",
      "longitude": "-9.13930000",
      "status": "suspeito",
      "doenca_id": 1,
      "user_id": 2,
      "created_at": "2026-01-21T06:05:27.000000Z",
      "updated_at": "2026-01-21T06:05:27.000000Z"
    }
  ],
  "links": { ... },
  "meta": { ... }
}
```

### Criar Novo Caso
```http
POST /api/casos
Authorization: Bearer TOKEN_HERE
Content-Type: application/json
Accept: application/json

{
  "paciente_nome": "Maria Santos",
  "idade": 42,
  "localizacao": "Porto",
  "data_inicio": "2026-01-21",
  "sintomas": "Febre, dores articulares, erupção cutânea",
  "latitude": 41.1579,
  "longitude": -8.6291,
  "status": "confirmado",
  "doenca_id": 1
}
```

**Resposta (201 Created):**
```json
{
  "id": 25,
  "paciente_nome": "Maria Santos",
  "idade": 42,
  "localizacao": "Porto",
  "data_inicio": "2026-01-21",
  "sintomas": "Febre, dores articulares, erupção cutânea",
  "latitude": "41.15790000",
  "longitude": "-8.62910000",
  "status": "confirmado",
  "doenca_id": 1,
  "user_id": 2,
  "created_at": "2026-01-21T06:10:00.000000Z",
  "updated_at": "2026-01-21T06:10:00.000000Z"
}
```

### Visualizar Caso Específico
```http
GET /api/casos/1
Authorization: Bearer TOKEN_HERE
Accept: application/json
```

### Atualizar Caso
```http
PUT /api/casos/1
Authorization: Bearer TOKEN_HERE
Content-Type: application/json
Accept: application/json

{
  "status": "confirmado",
  "sintomas": "Febre muito alta, dores intensas"
}
```

### Eliminar Caso
```http
DELETE /api/casos/1
Authorization: Bearer TOKEN_HERE
Accept: application/json
```

**Resposta:**
```json
{
  "message": "Caso eliminado com sucesso"
}
```

---

## 🚨 Testes CRUD de Alertas

### Criar Alerta
```http
POST /api/alertas
Authorization: Bearer TOKEN_HERE
Content-Type: application/json
Accept: application/json

{
  "titulo": "Alerta de Dengue Confirmada",
  "mensagem": "Novo caso confirmado de Dengue foi registado em Lisboa",
  "tipo": "email",
  "data_alerta": "2026-01-21 14:30:00",
  "caso_id": 1
}
```

### Listar Alertas
```http
GET /api/alertas?tipo=email&status=pendente
Authorization: Bearer TOKEN_HERE
Accept: application/json
```

### Atualizar Status de Alerta
```http
PUT /api/alertas/1
Authorization: Bearer TOKEN_HERE
Content-Type: application/json
Accept: application/json

{
  "status": "enviado"
}
```

---

## 📊 Testes CRUD de Relatórios

### Gerar Relatório
```http
POST /api/relatorios
Authorization: Bearer TOKEN_HERE
Content-Type: application/json
Accept: application/json

{
  "titulo": "Relatório Estatístico - Dengue Jan 2026",
  "tipo": "PDF",
  "formato_analise": "estatistico",
  "filtros": {
    "doenca_id": 1,
    "status": "confirmado"
  }
}
```

### Listar Meus Relatórios
```http
GET /api/relatorios
Authorization: Bearer TOKEN_HERE
Accept: application/json
```

### Visualizar Relatório
```http
GET /api/relatorios/1
Authorization: Bearer TOKEN_HERE
Accept: application/json
```

### Eliminar Relatório
```http
DELETE /api/relatorios/1
Authorization: Bearer TOKEN_HERE
Accept: application/json
```

---

## 🦠 Testes CRUD de Doenças (Admin Apenas)

### Listar Doenças
```http
GET /api/doencas
Authorization: Bearer TOKEN_ADMIN
Accept: application/json
```

### Criar Doença
```http
POST /api/doencas
Authorization: Bearer TOKEN_ADMIN
Content-Type: application/json
Accept: application/json

{
  "nome": "Ebola",
  "codigo": "EBO001",
  "descricao": "Doença viral grave com alta taxa de mortalidade",
  "status": "ativa"
}
```

### Atualizar Doença
```http
PUT /api/doencas/1
Authorization: Bearer TOKEN_ADMIN
Content-Type: application/json
Accept: application/json

{
  "status": "inativa"
}
```

### Eliminar Doença
```http
DELETE /api/doencas/1
Authorization: Bearer TOKEN_ADMIN
Accept: application/json
```

---

## 📢 Testes de Notificações (Admin Apenas)

### Criar Notificação
```http
POST /api/notificacaos
Authorization: Bearer TOKEN_ADMIN
Content-Type: application/json
Accept: application/json

{
  "titulo": "Dicas de Prevenção da Dengue",
  "conteudo": "Use repelente, feche janelas ao anoitecer, elimine recipientes com água parada",
  "tipo": "prevencao",
  "data_publicacao": "2026-01-21",
  "status": "ativa",
  "doenca_id": 1
}
```

### Listar Todas as Notificações (Admin)
```http
GET /api/notificacaos
Authorization: Bearer TOKEN_ADMIN
Accept: application/json
```

### Atualizar Notificação
```http
PUT /api/notificacaos/1
Authorization: Bearer TOKEN_ADMIN
Content-Type: application/json
Accept: application/json

{
  "status": "arquivada"
}
```

---

## ⛔ Testes de Controle de Acesso

### Teste 1: Público Tentando Acessar Casos
```http
GET /api/casos
Authorization: Bearer TOKEN_PUBLICO
Accept: application/json
```

**Resposta esperada (403):**
```json
"Acesso proibido. Você não tem permissão para acessar este recurso."
```

### Teste 2: Profissional Tentando Criar Doença
```http
POST /api/doencas
Authorization: Bearer TOKEN_PROFISSIONAL
Content-Type: application/json
Accept: application/json

{
  "nome": "Tuberculose",
  "codigo": "TUB001",
  "descricao": "...",
  "status": "ativa"
}
```

**Resposta esperada (403):**
```json
"Acesso proibido. Você não tem permissão para acessar este recurso."
```

---

## 🔍 Testes de Validação

### Criar Caso com Dados Inválidos
```http
POST /api/casos
Authorization: Bearer TOKEN_HERE
Content-Type: application/json
Accept: application/json

{
  "paciente_nome": "",
  "idade": 250,
  "latitude": 95,
  "doenca_id": 999
}
```

**Resposta esperada (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "paciente_nome": ["The paciente nome field is required."],
    "idade": ["The idade must be between 0 and 150."],
    "latitude": ["The latitude must be between -90 and 90."],
    "doenca_id": ["The selected doenca id is invalid."]
  }
}
```

---

## 📋 Checklist de Testes

- [ ] Login com credenciais corretas
- [ ] Login com credenciais incorretas
- [ ] Acessar rota pública sem autenticação
- [ ] Acessar rota protegida sem token
- [ ] Criar caso com dados válidos
- [ ] Filtrar casos por doença
- [ ] Criar alerta para caso
- [ ] Gerar relatório PDF
- [ ] Admin criar doença
- [ ] Profissional criar notificação (deve falhar)
- [ ] Público visualizar notificações
- [ ] Público tentar criar caso (deve falhar)
- [ ] Validar campos obrigatórios
- [ ] Validar tipos de dados
- [ ] Testar relacionamentos (caso com doença)

---

## 💡 Dicas

1. Use Postman Collections para organizar os testes
2. Salve tokens em variáveis de ambiente
3. Configure pre-request scripts para autenticação automática
4. Use assertions para validar respostas
5. Teste paginação com `?page=1&per_page=10`
6. Verifique status codes HTTP (200, 201, 400, 403, 404, 422)

---

## 🚀 Próximas Funcionalidades a Testar

- [ ] Autenticação com 2FA
- [ ] Download de relatórios
- [ ] Integração com mapas
- [ ] Envio de alertas por SMS/Email
- [ ] WebSockets para notificações em tempo real
- [ ] Gráficos e dashboards

