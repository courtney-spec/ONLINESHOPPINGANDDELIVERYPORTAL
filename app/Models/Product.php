<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'status',
    ];

    // Auto-generate slug from name
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name) . '-' . uniqid();
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= 5;
    }
}
