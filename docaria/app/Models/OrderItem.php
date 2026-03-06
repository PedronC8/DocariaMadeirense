<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_items';
    
    public $timestamps = false;
    
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'is_checked',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'is_checked' => 'boolean',
    ];

    // Relacionamentos

    /**
     * Encomenda associada
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Produto associado
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Acessores

    /**
     * Calcular subtotal do item (quantidade * preço do produto)
     */
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->product->price;
    }
}
