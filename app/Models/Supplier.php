<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'type', 'cnpj', 'name', 'status',
        'contact_name', 'contact_email', 'contact_phone', 'contact_site',
        'description', 'cep', 'address', 'number', 'complement',
        'neighborhood', 'state', 'city', 'country'
    ];
}
