<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'stock',     // opsional (lihat catatan di bawah)
        'category',
        'image',
    ];

    // ✅ relasi ke tabel product_variants
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
