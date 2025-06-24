<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cpf',
        'email',
        'phone',
        'role',
        'admission_date',
        'username',
        'password',
        'permission_level',
        'active',
    ];

    protected $hidden = ['password'];

    public function stockMovements()
    {
        return $this->hasMany(\App\Models\StockMovement::class, 'user_id');
    }
}
