<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'code',
    'total_amount',
    'status',
    'handling_status',
    'paid_at',
    'alamat',
    'city',
    'district',
    'postal_code',
    'telp'
    ];


    // user yang order
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // item-item dalam order
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // info pembayaran
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
