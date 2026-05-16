<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = ['user_id', 'total', 'status', 'payment_method', 'shipping_address', 'customer_name', 'customer_email', 'customer_phone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function delivery(): HasOne
{
    return $this->hasOne(\App\Models\Delivery::class);
}
}
