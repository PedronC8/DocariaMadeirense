<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';
    
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'label',
        'subcategory_id',
        'price',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

    // Relacionamentos

    /**
     * Subcategoria do produto
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    /**
     * Itens de encomenda que usam este produto
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // Scopes

    /**
     * Apenas produtos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // Acessores

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2, ',', '.') . '€';
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->active ? 'success' : 'secondary';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->active ? 'Ativo' : 'Inativo';
    }
}
