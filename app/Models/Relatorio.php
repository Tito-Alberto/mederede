<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relatorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'tipo',
        'formato_analise',
        'data_geracao',
        'filtros',
        'caminho_arquivo',
        'user_id',
    ];

    protected $casts = [
        'data_geracao' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
