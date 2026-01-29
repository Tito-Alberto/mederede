<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alerta extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'mensagem',
        'tipo',
        'status',
        'data_alerta',
        'caso_id',
        'user_id',
    ];

    protected $casts = [
        'data_alerta' => 'datetime',
    ];

    public function caso(): BelongsTo
    {
        return $this->belongsTo(Caso::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
