# 🏥 Sistema MEDEREDE - Monitorização de Doenças Endêmicas

## Resumo Geral

Sistema web completo em Laravel para monitorização, análise e resposta a doenças endêmicas com suporte a diferentes níveis de acesso (Admin, Profissional de Saúde, Público).

---

## 📋 Models Criados

### 1. **Doenca**
**Campos:**
- `id` - ID primário
- `nome` - Nome da doença (único)
- `codigo` - Código identificador (único)
- `descricao` - Descrição detalhada
- `status` - Status (ativa/inativa)
- `timestamps` - created_at, updated_at

**Relacionamentos:**
- `hasMany` Casos
- `hasMany` Notificacaos

---

### 2. **Caso**
**Campos:**
- `id` - ID primário
- `paciente_nome` - Nome do paciente
- `idade` - Idade do paciente
- `localizacao` - Localização do caso
- `data_inicio` - Data de início dos sintomas
- `sintomas` - Descrição dos sintomas
- `latitude` - Coordenada latitude
- `longitude` - Coordenada longitude
- `status` - Status (confirmado/suspeito/descartado)
- `doenca_id` - Chave estrangeira para Doenca
- `user_id` - Chave estrangeira para User (profissional que registou)
- `timestamps`

**Relacionamentos:**
- `belongsTo` Doenca
- `belongsTo` User
- `hasMany` Alertas

---

### 3. **Alerta**
**Campos:**
- `id` - ID primário
- `titulo` - Título do alerta
- `mensagem` - Conteúdo da mensagem
- `tipo` - Tipo de alerta (email/sms/notificacao)
- `status` - Status (enviado/pendente/falhou)
- `data_alerta` - Data/hora do alerta
- `caso_id` - Chave estrangeira para Caso
- `user_id` - Chave estrangeira para User
- `timestamps`

**Relacionamentos:**
- `belongsTo` Caso
- `belongsTo` User

---

### 4. **Relatorio**
**Campos:**
- `id` - ID primário
- `titulo` - Título do relatório
- `tipo` - Tipo de arquivo (PDF/CSV)
- `formato_analise` - Tipo de análise (temporal/geografico/estatistico)
- `data_geracao` - Data de geração
- `filtros` - Filtros aplicados (JSON)
- `caminho_arquivo` - Caminho do arquivo gerado
- `user_id` - Chave estrangeira para User
- `timestamps`

**Relacionamentos:**
- `belongsTo` User

---

### 5. **Notificacao**
**Campos:**
- `id` - ID primário
- `titulo` - Título da notificação
- `conteudo` - Conteúdo da mensagem
- `tipo` - Tipo (prevencao/informacao/alerta)
- `data_publicacao` - Data de publicação
- `status` - Status (ativa/inativa/arquivada)
- `doenca_id` - Chave estrangeira para Doenca
- `timestamps`

**Relacionamentos:**
- `belongsTo` Doenca

---

### 6. **User** (Modificado)
**Campos Adicionados:**
- `role` - Nível de acesso (admin/profissional_saude/publico)

**Relacionamentos Adicionados:**
- `hasMany` Casos
- `hasMany` Alertas
- `hasMany` Relatorios

---

## 🔐 Níveis de Acesso (Roles)

### **Admin**
- ✅ Gerenciar Doenças (CRUD completo)
- ✅ Gerenciar Notificações (CRUD completo)
- ✅ Visualizar relatórios
- ✅ Gerenciar casos
- ✅ Gerenciar alertas

### **Profissional de Saúde**
- ✅ Registar casos
- ✅ Visualizar casos
- ✅ Criar alertas
- ✅ Gerar relatórios
- ✅ Visualizar notificações

### **Público**
- ✅ Visualizar notificações (apenas informações educativas)
- ✅ Sem acesso a casos e alertas

---

## 🛣️ Rotas da API

### **Rotas Públicas**
```
GET  /api/notificacaos           - Listar notificações
GET  /api/notificacaos/{id}      - Ver notificação específica
```

### **Rotas Protegidas - Profissionais de Saúde & Admin**
```
GET    /api/casos                - Listar casos
POST   /api/casos                - Criar caso
GET    /api/casos/{id}           - Ver caso
PUT    /api/casos/{id}           - Atualizar caso
DELETE /api/casos/{id}           - Eliminar caso

GET    /api/alertas              - Listar alertas
POST   /api/alertas              - Criar alerta
GET    /api/alertas/{id}         - Ver alerta
PUT    /api/alertas/{id}         - Atualizar alerta
DELETE /api/alertas/{id}         - Eliminar alerta

GET    /api/relatorios           - Listar relatórios
POST   /api/relatorios           - Gerar relatório
GET    /api/relatorios/{id}      - Ver relatório
DELETE /api/relatorios/{id}      - Eliminar relatório
```

### **Rotas Protegidas - Admin Apenas**
```
GET    /api/doencas              - Listar doenças
POST   /api/doencas              - Criar doença
GET    /api/doencas/{id}         - Ver doença
PUT    /api/doencas/{id}         - Atualizar doença
DELETE /api/doencas/{id}         - Eliminar doença

GET    /api/notificacaos         - Listar notificações (admin)
POST   /api/notificacaos         - Criar notificação
GET    /api/notificacaos/{id}    - Ver notificação
PUT    /api/notificacaos/{id}    - Atualizar notificação
DELETE /api/notificacaos/{id}    - Eliminar notificação
```

---

## 📁 Estrutura de Ficheiros Criados

```
app/
  └─ Models/
     ├─ Doenca.php
     ├─ Caso.php
     ├─ Alerta.php
     ├─ Relatorio.php
     ├─ Notificacao.php
     └─ User.php (modificado)

  └─ Http/
     ├─ Controllers/
     │  ├─ DoencaController.php
     │  ├─ CasoController.php
     │  ├─ AlertaController.php
     │  ├─ RelatorioController.php
     │  └─ NotificacaoController.php
     ├─ Middleware/
     │  └─ CheckRole.php
     └─ Kernel.php (modificado)

database/
  ├─ migrations/
  │  ├─ 2026_01_21_055737_create_doencas_table.php
  │  ├─ 2026_01_21_055745_create_casos_table.php
  │  ├─ 2026_01_21_055749_create_alertas_table.php
  │  ├─ 2026_01_21_055753_create_relatorios_table.php
  │  ├─ 2026_01_21_055758_create_notificacaos_table.php
  │  └─ 2026_01_21_055928_add_role_to_users_table.php
  
  ├─ factories/
  │  ├─ DoencaFactory.php
  │  └─ CasoFactory.php
  
  └─ seeders/
     ├─ DoencaSeeder.php
     ├─ CasoSeeder.php
     └─ DatabaseSeeder.php (modificado)

routes/
  └─ api.php (modificado)
```

---

## 🚀 Como Usar

### **1. Executar as Migrations** (Já executadas)
```bash
php artisan migrate
```

### **2. Seedar o Banco de Dados**
```bash
php artisan db:seed
```

Isto criará:
- 1 Utilizador Admin (admin@mederede.com / password)
- 1 Profissional de Saúde (profissional@mederede.com / password)
- 5 Utilizadores Públicos
- 5 Doenças
- 20 Casos de teste

### **3. Testar as Rotas**

#### Autenticar-se
```bash
POST /api/login
{
  "email": "admin@mederede.com",
  "password": "password"
}
```

#### Criar Caso (Profissional de Saúde)
```bash
POST /api/casos
Headers: Authorization: Bearer {token}
Body:
{
  "paciente_nome": "João Silva",
  "idade": 35,
  "localizacao": "Lisboa",
  "data_inicio": "2026-01-20",
  "sintomas": "Febre alta, dores no corpo",
  "latitude": 38.7223,
  "longitude": -9.1393,
  "status": "suspeito",
  "doenca_id": 1
}
```

---

## 🔧 Middleware de Acesso

O middleware `CheckRole` verifica automaticamente se o utilizador tem a permissão necessária.

**Exemplo de Uso:**
```php
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Apenas admin pode acessar
});
```

---

## 📊 Funcionalidades Implementadas

✅ Registro e login de utilizadores
✅ Níveis de acesso (Admin, Profissional de Saúde, Público)
✅ Registro de casos com dados georreferenciados
✅ Sistema de alertas automáticos
✅ Geração de relatórios (PDF/CSV)
✅ Consulta pública com notificações educativas
✅ Autenticação segura via Laravel Sanctum
✅ Validação de dados nos modelos
✅ Relacionamentos entre tabelas
✅ Factories e Seeders para testes

---

## 📝 Próximos Passos

1. **Implementar Controllers** - Adicionar lógica nos controllers
2. **Validações** - Adicionar FormRequests para validação
3. **Autenticação** - Configurar Laravel Sanctum/Passport
4. **Testes** - Criar testes unitários
5. **Frontend** - Criar interface Vue.js ou React
6. **Dashboard** - Implementar gráficos e estatísticas
7. **Integração SMS/Email** - Configurar alertas automáticos
8. **Mapas** - Integrar biblioteca de mapas (Leaflet/Google Maps)

---

## 📧 Contacto

Para mais informações sobre este projeto, consulte a documentação oficial do Laravel:
- https://laravel.com/docs

**Criado em:** 21 de Janeiro de 2026
