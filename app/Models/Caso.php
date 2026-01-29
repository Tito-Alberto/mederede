<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caso extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_nome',
        'localizacao',
        'data_inicio',
        'sintomas',
        'latitude',
        'longitude',
        'status',
        'doenca_id',
        'user_id',
        'bilhete',
        'data_nascimento',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_nascimento' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function doenca(): BelongsTo
    {
        return $this->belongsTo(Doenca::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function alertas(): HasMany
    {
        return $this->hasMany(Alerta::class);
    }
}
