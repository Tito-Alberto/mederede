<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doenca extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
        'status',
    ];

    public function casos(): HasMany
    {
        return $this->hasMany(Caso::class);
    }

    public function notificacaos(): HasMany
    {
        return $this->hasMany(Notificacao::class);
    }
}
