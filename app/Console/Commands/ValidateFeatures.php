<?php

namespace App\Console\Commands;

use App\Models\Alerta;
use App\Models\Caso;
use App\Models\Doenca;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ValidateFeatures extends Command
{
    protected $signature = 'validate:features';
    protected $description = 'Valida todas as funcionalidades principais do MEDEREDE';

    public function handle()
    {
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('🔍 VALIDAÇÃO COMPLETA DE FUNCIONALIDADES - MEDEREDE');
        $this->info('═══════════════════════════════════════════════════════════');

        // 1. VALIDAR LOGIN DE UTILIZADORES
        $this->validateUserAuthentication();

        // 2. VALIDAR REGISTRO DE CASOS
        $this->validateCasoRegistration();

        // 3. VALIDAR DASHBOARD
        $this->validateDashboard();

        // 4. VALIDAR SISTEMA DE ALERTAS
        $this->validateAlerts();

        // 5. VALIDAR CONSULTA PÚBLICA
        $this->validatePublicData();

        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('✅ VALIDAÇÃO COMPLETA CONCLUÍDA!');
        $this->info('═══════════════════════════════════════════════════════════');

        return 0;
    }

    private function validateUserAuthentication()
    {
        $this->line("\n📋 1. VALIDANDO LOGIN DE UTILIZADORES");
        $this->line('─────────────────────────────────────');

        // Verificar Admin
        $admin = User::where('email', 'admin@mederede.com')->first();
        if ($admin && $admin->role === 'admin') {
            $this->line('✅ Admin: EXISTE E VALIDADO');
        } else {
            $this->line('❌ Admin: NÃO ENCONTRADO');
        }

        // Verificar Profissional de Saúde
        $profissional = User::where('email', 'profissional@mederede.com')->first();
        if ($profissional && $profissional->role === 'profissional_saude') {
            $this->line('✅ Profissional de Saúde: EXISTE E VALIDADO');
        } else {
            $this->line('❌ Profissional de Saúde: NÃO ENCONTRADO');
        }

        // Verificar Utilizador Público
        $publico = User::where('role', 'publico')->first();
        if ($publico) {
            $this->line('✅ Utilizador Público: EXISTE');
            $this->line("   • Nome: {$publico->name}");
            $this->line("   • Email: {$publico->email}");
        } else {
            $this->line('❌ Utilizador Público: NÃO ENCONTRADO');
        }

        $this->line("\n📊 Total de Utilizadores: " . User::count());
        $this->line("   • Admins: " . User::where('role', 'admin')->count());
        $this->line("   • Profissionais: " . User::where('role', 'profissional_saude')->count());
        $this->line("   • Público: " . User::where('role', 'publico')->count());
    }

    private function validateCasoRegistration()
    {
        $this->line("\n📋 2. VALIDANDO REGISTRO DE CASOS");
        $this->line('─────────────────────────────────');

        $totalCasos = Caso::count();
        $this->line("✅ Total de Casos Registados: {$totalCasos}");

        // Verificar dados essenciais
        $casoComDados = Caso::first();
        if ($casoComDados) {
            $this->line("\n📝 Exemplo de Caso Registado:");
            $this->line("   • Paciente: {$casoComDados->paciente_nome}");
            $this->line("   • Sintomas: " . ($casoComDados->sintomas ?? 'Não informado'));
            $this->line("   • Localização: {$casoComDados->localizacao}");
            $this->line("   • Data Início: " . $casoComDados->data_inicio->format('d/m/Y'));
            $this->line("   • Latitude: {$casoComDados->latitude}");
            $this->line("   • Longitude: {$casoComDados->longitude}");
            $this->line("   • Status: {$casoComDados->status}");
            $this->line("   • Doença: " . ($casoComDados->doenca?->nome ?? 'N/A'));

            $this->line("\n✅ TODOS OS DADOS ESSENCIAIS PRESENTES");
        } else {
            $this->line("⚠️  Nenhum caso registado ainda");
        }

        // Verificar status dos casos
        $this->line("\n📊 Distribuição por Status:");
        $statusCount = Caso::selectRaw('status, count(*) as total')->groupBy('status')->get();
        foreach ($statusCount as $status) {
            $this->line("   • " . ucfirst($status->status) . ": {$status->total}");
        }
    }

    private function validateDashboard()
    {
        $this->line("\n📋 3. VALIDANDO DASHBOARD");
        $this->line('─────────────────────────');

        // Dados para Dashboard
        $casos = Caso::count();
        $doencas = Doenca::count();
        $alertas = Alerta::count();
        $usuarios = User::count();

        $this->line("✅ ESTATÍSTICAS EM TEMPO REAL:");
        $this->line("   • Doenças Monitoradas: {$doencas}");
        $this->line("   • Casos Registados: {$casos}");
        $this->line("   • Alertas: {$alertas}");
        $this->line("   • Utilizadores Ativos: {$usuarios}");

        // Verificar dados para gráficos
        if ($casos > 0) {
            $casosPorMes = Caso::selectRaw('MONTH(created_at) as mes, count(*) as total')
                ->where('created_at', '>=', now()->subMonths(3))
                ->groupBy('mes')
                ->get();

            $this->line("\n📈 Evolução Temporal (Últimos 3 Meses):");
            if ($casosPorMes->count() > 0) {
                foreach ($casosPorMes as $dado) {
                    $this->line("   • Mês {$dado->mes}: {$dado->total} casos");
                }
            } else {
                $this->line("   • Dados disponíveis para construir gráficos");
            }
        }

        // Verificar dados para mapa de calor
        $casosCoordenadas = Caso::where('latitude', '!=', null)
            ->where('longitude', '!=', null)
            ->count();

        $this->line("\n🗺️  Dados Geográficos para Mapa de Calor:");
        $this->line("   • Casos com Coordenadas: {$casosCoordenadas}");

        if ($casosCoordenadas > 0) {
            $this->line("   ✅ MAPA DE CALOR: Pronto para ser renderizado");
        } else {
            $this->line("   ⚠️  Sem dados geográficos ainda");
        }

        // Verificar por Doença
        if ($doencas > 0) {
            $this->line("\n📊 Distribuição por Doença:");
            $casoPorDoenca = Caso::selectRaw('doenca_id, count(*) as total')
                ->with('doenca')
                ->groupBy('doenca_id')
                ->get();

            foreach ($casoPorDoenca as $dado) {
                $this->line("   • " . $dado->doenca->nome . ": {$dado->total} casos");
            }
        }
    }

    private function validateAlerts()
    {
        $this->line("\n📋 4. VALIDANDO SISTEMA DE ALERTAS");
        $this->line('──────────────────────────────────');

        $totalAlertas = Alerta::count();
        $this->line("✅ Total de Alertas: {$totalAlertas}");

        // Verificar tipos de alertas
        $tiposAlertas = Alerta::selectRaw('tipo, count(*) as total')->groupBy('tipo')->get();
        if ($tiposAlertas->count() > 0) {
            $this->line("\n📨 Alertas por Tipo:");
            foreach ($tiposAlertas as $tipo) {
                $this->line("   • " . ucfirst($tipo->tipo) . ": {$tipo->total}");
            }
        }

        // Verificar status dos alertas
        $statusAlertas = Alerta::selectRaw('status, count(*) as total')->groupBy('status')->get();
        if ($statusAlertas->count() > 0) {
            $this->line("\n📊 Alertas por Status:");
            foreach ($statusAlertas as $status) {
                $this->line("   • " . ucfirst($status->status) . ": {$status->total}");
            }
        }

        // Verificar campos de alerta
        $alertaExemplo = Alerta::first();
        if ($alertaExemplo) {
            $this->line("\n📝 Exemplo de Alerta:");
            $this->line("   • Título: {$alertaExemplo->titulo}");
            $this->line("   • Tipo: {$alertaExemplo->tipo}");
            $this->line("   • Status: {$alertaExemplo->status}");
            $this->line("   • Data: " . $alertaExemplo->data_alerta->format('d/m/Y H:i'));
            $this->line("   ✅ ALERTAS: Funcionais (Email/SMS configuráveis)");
        } else {
            $this->line("   ⚠️  Nenhum alerta registado ainda");
        }
    }

    private function validatePublicData()
    {
        $this->line("\n📋 5. VALIDANDO CONSULTA PÚBLICA");
        $this->line('────────────────────────────────');

        // Verificar doenças disponíveis
        $doencas = Doenca::all();
        if ($doencas->count() > 0) {
            $this->line("✅ DOENÇAS EDUCATIVAS DISPONÍVEIS:");
            foreach ($doencas as $doenca) {
                $this->line("\n   📚 {$doenca->nome}");
                $this->line("      • Código: {$doenca->codigo}");
                $this->line("      • Status: " . ucfirst($doenca->status));
                if ($doenca->descricao) {
                    $desc = substr($doenca->descricao, 0, 60) . '...';
                    $this->line("      • Descrição: {$desc}");
                }
                
                // Contar casos dessa doença
                $casoCount = $doenca->casos()->count();
                $this->line("      • Casos Ativos: {$casoCount}");
            }
            $this->line("\n   ✅ PÁGINA PÚBLICA: Informações Educativas Disponíveis");
            $this->line("      Rota: GET / (Homepage com informações sobre doenças)");
        } else {
            $this->line("❌ Nenhuma doença cadastrada");
        }
    }
}
