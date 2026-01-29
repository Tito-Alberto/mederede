<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'conteudo',
        'tipo',
        'data_publicacao',
        'status',
        'doenca_id',
    ];

    protected $casts = [
        'data_publicacao' => 'date',
    ];

    public function doenca(): BelongsTo
    {
        return $this->belongsTo(Doenca::class);
    }
}
