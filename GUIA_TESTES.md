# 🧪 GUIA RÁPIDO DE TESTES - MEDEREDE

## 🚀 Como Testar Cada Funcionalidade

---

## 1️⃣ LOGIN DE UTILIZADORES

### Teste Admin:
1. Acesse: `http://127.0.0.1:8000/login`
2. Email: `admin@mederede.com`
3. Senha: `password`
4. Clique "Entrar"
5. ✅ Esperado: Redirecionamento para Dashboard com acesso completo

### Teste Profissional de Saúde:
1. Acesse: `http://127.0.0.1:8000/login`
2. Email: `profissional@mederede.com`
3. Senha: `password`
4. Clique "Entrar"
5. ✅ Esperado: Dashboard com funcionalidades limitadas

### Teste Público:
1. Acesse: `http://127.0.0.1:8000`
2. Veja informações educativas sobre doenças
3. ✅ Esperado: Acesso sem autenticação

### Teste QR Code com Login:
1. Após login, acesse: `http://127.0.0.1:8000/qrcode`
2. Veja lista de utilizadores
3. Clique no ícone QR do Admin
4. Veja QR Code gerado: `1234567890|1990-01-15|Admin`
5. ✅ Esperado: QR Code visível e baixável

---

## 2️⃣ REGISTRO DE CASOS

### Criar Novo Caso:
1. Faça login (admin@mederede.com)
2. Clique "➕ Registar Novo Caso" no Dashboard
3. Preencha o formulário:
   - **Paciente:** João Silva
   - **Idade:** 35
   - **Doença:** Dengue
   - **Status:** Confirmado
   - **Data Início:** 25/01/2026
   - **Localização:** Lisboa
   - **Latitude:** 38.7223
   - **Longitude:** -9.1393
   - **Sintomas:** Febre alta, mal estar

4. Clique "Guardar"
5. ✅ Esperado: Redirecionamento para lista de casos com mensagem de sucesso

### Ver Casos:
1. No Dashboard, você vê:
   - "Casos Registados: 20" (ou mais)
   - Últimos 5 casos recentes
2. Clique "📝 Ver Casos" para lista completa
3. ✅ Esperado: Tabela com todos os casos, paginados

### Editar Caso:
1. Na lista de casos, clique no caso
2. Clique "Editar"
3. Modifique dados (ex: Status para "Descartado")
4. Clique "Atualizar"
5. ✅ Esperado: Caso atualizado com sucesso

### Eliminar Caso:
1. Na lista de casos, clique "Eliminar"
2. Confirme ação
3. ✅ Esperado: Caso removido da lista

---

## 3️⃣ DASHBOARD COM GRÁFICOS

### Acessar Dashboard:
1. Após login, acesse: `http://127.0.0.1:8000/dashboard`
2. Você vê:
   - 📊 Estatísticas (5 cards no topo)
   - 📈 Gráfico de Evolução Temporal
   - 🦠 Gráfico de Doenças (Doughnut)
   - 📊 Gráfico de Status (Barras)
   - 🗺️ Dados Geográficos
   - 🚨 Alertas Recentes
   - 📝 Casos Recentes

### Testar Gráficos:
1. **Evolução Temporal:** 
   - Vê linha com pontos
   - Mostra casos por mês
   - Hover para ver valores

2. **Distribuição por Doença:**
   - Gráfico circular colorido
   - Representa percentual de casos
   - Clique na legenda para filtrar

3. **Casos por Status:**
   - Gráfico de barras vertical
   - Barras em cores diferentes
   - Vermelho (Confirmado), Amarelo (Suspeito), Verde (Descartado)

4. **Dados Geográficos:**
   - Mostra "20 casos com localização"
   - Pronto para integração de Mapa

### Verificar Dados em Tempo Real:
1. Os números dos cards mudam conforme casos são adicionados
2. Os gráficos se atualizam automaticamente
3. ✅ Esperado: Tudo dinâmico e em tempo real

---

## 4️⃣ SISTEMA DE ALERTAS

### Ver Alertas:
1. Após login, clique "🚨 Gerenciar Alertas"
2. Você vê lista de alertas (vazia ou com alertas)
3. ✅ Esperado: Tabela com alertas, paginados

### Criar Novo Alerta:
1. Clique "➕ Novo Alerta"
2. Preencha:
   - **Caso:** Selecione um caso da lista
   - **Título:** "Dengue ativa em Lisboa"
   - **Tipo:** Email
   - **Mensagem:** "Alerta de dengue confirmada"
   - **Data:** 27/01/2026

3. Clique "Enviar"
4. ✅ Esperado: Alerta criado com sucesso

### Ver Alerta no Dashboard:
1. Volte ao Dashboard
2. Veja seu alerta em "🚨 Alertas Recentes"
3. ✅ Esperado: Alerta aparece imediatamente

### Tipos de Alertas Disponíveis:
- **Email:** Para notificação por correio
- **SMS:** Para mensagens de texto
- **Notificação:** Para alerta no sistema

---

## 5️⃣ CONSULTA PÚBLICA

### Acessar Página Pública:
1. Acesse: `http://127.0.0.1:8000` (sem login)
2. Você vê:
   - Homepage com design profissional
   - Informações sobre MEDEREDE
   - Seção de doenças educativas

### Ver Informações de Doenças:
1. Scroll para baixo na homepage
2. Você vê 5 doenças com:
   - Dengue (6 casos)
   - Malária (2 casos)
   - Febre Amarela (1 caso)
   - Zika (5 casos)
   - Chikungunya (6 casos)

3. Cada doença mostra:
   - Nome da doença
   - Código (ex: DEN001)
   - Status (Ativa/Inativa)
   - Número de casos
   - Descrição educativa

### Acessar Login:
1. No menu superior, clique "Login"
2. Você é redirecionado para página de login
3. ✅ Esperado: Formulário de autenticação

---

## 📊 VERIFICAÇÃO RÁPIDA

Execute no terminal:
```bash
cd c:\laragon\www\Mederede
php artisan validate:features
```

Você verá um relatório completo com ✅ para cada funcionalidade.

---

## 🔧 COMANDOS ÚTEIS

### Listar rotas:
```bash
php artisan route:list
```

### Ver banco de dados:
```bash
php artisan tinker
```

### Resetar dados:
```bash
php artisan migrate:refresh --seed
```

### Executar testes QR Code:
```bash
php artisan test:qrcode
```

---

## ⚠️ TROUBLESHOOTING

### "Erro de Conexão":
1. Verifique se Laragon está rodando
2. Reinicie o servidor: `php artisan serve`

### "Credenciais inválidas":
1. Use exatamente: `admin@mederede.com` / `password`
2. Não deixe espaços em branco

### "Gráficos não aparecem":
1. Abra DevTools (F12)
2. Verifique Console para erros JavaScript
3. Certifique-se que Chart.js carregou

### "Casos não aparecem":
1. Faça login primeiro
2. Vá para `/casos` para criar novo caso
3. Refresque a página (F5)

---

## ✅ CHECKLIST DE FUNCIONALIDADES

- [ ] Login com admin@mederede.com funciona
- [ ] Dashboard carrega com estatísticas
- [ ] Gráficos de evolução temporal exibem
- [ ] Gráfico de doenças mostra distribuição
- [ ] Gráfico de status mostra 3 categorias
- [ ] Pode criar novo caso
- [ ] Pode editar caso existente
- [ ] Pode deletar caso
- [ ] Pode criar alerta
- [ ] Dashboard mostra alertas recentes
- [ ] Página pública exibe doenças
- [ ] QR Code pode ser gerado
- [ ] Logout funciona

---

## 📞 SUPORTE

Se encontrar problemas, verifique:
1. **Logs:** `storage/logs/laravel.log`
2. **Database:** Use `php artisan tinker`
3. **Rotas:** Execute `php artisan route:list`

---

**Sistema MEDEREDE - 27 de Janeiro de 2026**
✅ Todas as funcionalidades testáveis e funcionais!
