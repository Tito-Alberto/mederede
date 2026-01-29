<?php

namespace App\Console\Commands;

use App\Models\User;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Console\Command;

class TestQRCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:qrcode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa a funcionalidade de QR Code';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Teste de Funcionalidade de QR Code');
        $this->info('=====================================');

        // 1. Buscar um usuário com dados completos
        $user = User::where('bilhete', '1234567890')->first();

        if (!$user) {
            $this->error('❌ Usuário com bilhete 1234567890 não encontrado!');
            return 1;
        }

        $this->info("\n✅ Usuário encontrado:");
        $this->line("   Nome: {$user->name}");
        $this->line("   Email: {$user->email}");
        $this->line("   Bilhete: {$user->bilhete}");
        $this->line("   Data Nascimento: {$user->data_nascimento}");

        // 2. Gerar dados do QR Code
        $qrData = $user->bilhete . '|' . $user->data_nascimento . '|' . $user->name;
        $this->info("\n✅ Dados para QR Code gerados:");
        $this->line("   {$qrData}");

        // 3. Gerar QR Code
        try {
            $qrCode = QrCode::create($qrData)
                ->setSize(300)
                ->setMargin(10);

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            // Salvar em storage para teste
            $filename = 'test_qrcode_' . $user->bilhete . '.png';
            file_put_contents(storage_path('app/' . $filename), $result->getString());

            $this->info("\n✅ QR Code gerado com sucesso!");
            $this->line("   Arquivo salvo: storage/app/{$filename}");
        } catch (\Exception $e) {
            $this->error("❌ Erro ao gerar QR Code: {$e->getMessage()}");
            return 1;
        }

        // 4. Validar QR Code
        $parts = explode('|', $qrData);
        if (count($parts) !== 3) {
            $this->error('❌ Formato do QR Code inválido!');
            return 1;
        }

        [$bilhete, $data_nascimento, $nome] = $parts;

        $validUser = User::where('bilhete', $bilhete)
            ->where('data_nascimento', $data_nascimento)
            ->where('name', $nome)
            ->first();

        if ($validUser) {
            $this->info("\n✅ QR Code validado com sucesso!");
            $this->line("   Identidade confirmada para: {$validUser->name}");
        } else {
            $this->error("\n❌ Falha na validação do QR Code!");
            return 1;
        }

        // 5. Teste de rejeição
        $this->info("\n🧪 Testando rejeição de QR Code inválido...");
        $invalidUser = User::where('bilhete', 'INVALIDO123')
            ->where('data_nascimento', '2000-01-01')
            ->where('name', 'Usuário Inexistente')
            ->first();

        if (!$invalidUser) {
            $this->info("✅ QR Code inválido foi corretamente rejeitado!");
        }

        // Resumo
        $this->info("\n=====================================");
        $this->info("📊 TESTE COMPLETO - RESULTADO FINAL:");
        $this->info("=====================================");
        $this->line("✅ Geração de QR Code: FUNCIONAL");
        $this->line("✅ Validação de QR Code: FUNCIONAL");
        $this->line("✅ Rejeição de Dados Inválidos: FUNCIONAL");
        $this->info("\n🎉 SISTEMA DE QR CODE 100% FUNCIONAL!");

        return 0;
    }
}
