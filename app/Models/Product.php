<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'internal_code',
        'description',
        'category_id',
        'unit',
        'cost_price',
        'sale_price',
        'min_stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
