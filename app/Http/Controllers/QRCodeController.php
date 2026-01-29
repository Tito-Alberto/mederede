<?php

namespace App\Http\Controllers;

use App\Models\User;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    /**
     * Gerar QR Code de validação para um usuário
     * Dados: bilhete + data_nascimento + nome
     */
    public function generate(User $user)
    {
        // Validar que o usuário tem os dados necessários
        if (!$user->bilhete || !$user->data_nascimento || !$user->name) {
            return back()->with('error', 'Usuário não tem dados completos para gerar QR Code');
        }

        // Criar dados para o QR Code (formato: bilhete|data_nascimento|nome)
        $qrData = $user->bilhete . '|' . $user->data_nascimento . '|' . $user->name;

        // Gerar QR Code
        $qrCode = QrCode::create($qrData)
            ->setSize(300)
            ->setMargin(10);

        // Criar writer
        $writer = new PngWriter();

        return view('qrcode.show', [
            'user' => $user,
            'qrCode' => $writer->write($qrCode),
            'qrData' => $qrData,
        ]);
    }

    /**
     * Página para validar QR Code (leitura)
     */
    public function showValidate()
    {
        return view('qrcode.validate');
    }

    /**
     * Processar validação de QR Code
     */
    public function checkValidity(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
        ]);

        // Decodificar QR Code (formato: bilhete|data_nascimento|nome)
        $parts = explode('|', $request->qr_data);

        if (count($parts) !== 3) {
            return back()->with('error', 'QR Code inválido');
        }

        [$bilhete, $data_nascimento, $nome] = $parts;

        // Buscar usuário com esses dados
        $user = User::where('bilhete', $bilhete)
            ->where('data_nascimento', $data_nascimento)
            ->where('name', $nome)
            ->first();

        if (!$user) {
            return back()->with('error', 'Dados não encontrados na base de dados');
        }

        // QR Code válido
        return view('qrcode.valid', [
            'user' => $user,
            'message' => 'Identidade validada com sucesso!',
        ]);
    }

    /**
     * Listar usuários com opção de gerar QR Code
     */
    public function listUsers()
    {
        $users = User::paginate(10);
        return view('qrcode.list', ['users' => $users]);
    }

    /**
     * Download QR Code como imagem
     */
    public function download(User $user)
    {
        // Validar que o usuário tem os dados necessários
        if (!$user->bilhete || !$user->data_nascimento || !$user->name) {
            return back()->with('error', 'Usuário não tem dados completos');
        }

        // Criar dados para o QR Code
        $qrData = $user->bilhete . '|' . $user->data_nascimento . '|' . $user->name;

        // Gerar QR Code
        $qrCode = QrCode::create($qrData)
            ->setSize(500)
            ->setMargin(10);

        // Criar writer
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Download como imagem
        return response($result->getString(), 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qrcode_' . $user->bilhete . '.png"');
    }
}
