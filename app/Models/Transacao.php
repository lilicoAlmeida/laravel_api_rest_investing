<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;

    protected $fillable = ['investimento_id', 'tipo', 'quantidade', 'data'];

    protected $table = 'transacoes';

    public function investimento()
    {
        return $this->belongsTo(Investimento::class);
    }
}
