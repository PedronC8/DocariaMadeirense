<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'clients';
    
    public $timestamps = false;
    
    protected $fillable = [
        'idFaturacao',
        'name',
        'nif',
        'contact',
        'address',
    ];

    // Relacionamentos

    /**
     * Encomendas do cliente
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    // Acessores

    /**
     * Get formatted ID Faturacao
     */
    public function getFormattedIdAttribute(): string
    {
        return $this->idFaturacao ?? 'N/A';
    }
}
