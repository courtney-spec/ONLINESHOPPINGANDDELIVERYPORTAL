<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Delivery extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'delivery_address',
        'recipient_name',
        'recipient_phone',
        'courier_name',
        'tracking_number',
        'estimated_delivery_date',
        'dispatched_at',
        'delivered_at',
        'notes',
    ];

    protected $casts = [
        'estimated_delivery_date' => 'date',
        'dispatched_at'           => 'datetime',
        'delivered_at'            => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'          => 'Pending',
            'dispatched'       => 'Dispatched',
            'out_for_delivery' => 'Out for Delivery',
            'delivered'        => 'Delivered',
            'failed'           => 'Failed',
            default            => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'          => 'bg-yellow-100 text-yellow-700',
            'dispatched'       => 'bg-blue-100 text-blue-700',
            'out_for_delivery' => 'bg-orange-100 text-orange-700',
            'delivered'        => 'bg-green-100 text-green-700',
            'failed'           => 'bg-red-100 text-red-700',
            default            => 'bg-gray-100 text-gray-700',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'pending'          => '🕐',
            'dispatched'       => '📦',
            'out_for_delivery' => '🚚',
            'delivered'        => '✅',
            'failed'           => '❌',
            default            => '❓',
        };
    }

    public static function generateTrackingNumber(): string
    {
        do {
            $number = 'SS-' . strtoupper(Str::random(8));
        } while (self::where('tracking_number', $number)->exists());
        return $number;
    }
}
