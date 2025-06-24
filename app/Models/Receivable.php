<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'pessoa',
        'categoria',
        'valor',
        'data_vencimento',
        'data_recebimento',
        'status',
        'forma_recebimento',
        'observacoes',
        'comprovante',
        'criado_por',
    ];

    public function criador()
    {
        return $this->belongsTo(Employee::class, 'criado_por');
    }
}
