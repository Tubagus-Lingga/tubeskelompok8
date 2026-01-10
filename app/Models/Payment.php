<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $casts = [
        'payload' => 'array'
    ];

    protected $fillable = [
        'order_id','provider','amount','status','payload'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
