<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = ['order_id','provider','reference','amount','status','paid_at','payload'];
    protected $casts = ['paid_at' => 'datetime', 'payload' => 'array'];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
