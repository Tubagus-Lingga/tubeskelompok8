<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Nama tabel (opsional jika tabel = 'products')
     */
    protected $table = 'products';

    /**
     * Field yang boleh diisi mass-assignment
     */
    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'stock',
        'category_id',
        'image',
    ];
}
