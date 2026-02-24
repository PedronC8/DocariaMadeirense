<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';
    
    public $timestamps = false;
    
    protected $fillable = [
        'client_id',
        'vendedor_id',
        'trabalhador_id',
        'invoice',
        'order_date',
        'ready_date',
        'delivery_date',
        'desired_date',
        'subtotal',
        'iva',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'ready_date' => 'date',
        'delivery_date' => 'date',
        'desired_date' => 'date',
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relacionamentos
    
    /**
     * Cliente associado à encomenda
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Vendedor que criou a encomenda
     */
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    /**
     * Trabalhador responsável pela preparação
     */
    public function trabalhador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trabalhador_id');
    }

    /**
     * Itens da encomenda
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Acessores

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'preparacao' => 'warning',
            'concluido' => 'info',
            'entregue' => 'success',
            default => 'secondary'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'preparacao' => 'Em Preparação',
            'concluido' => 'Concluído',
            'entregue' => 'Entregue',
            default => 'Desconhecido'
        };
    }

    /**
     * Get payment status badge color
     */
    public function getPaymentStatusBadgeAttribute(): string
    {
        return match($this->payment_status) {
            'nao_pago' => 'danger',
            'parcial' => 'warning',
            'pago' => 'success',
            default => 'secondary'
        };
    }

    /**
     * Get payment status label
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'nao_pago' => 'Não Pago',
            'parcial' => 'Parcial',
            'pago' => 'Pago',
            default => 'Desconhecido'
        };
    }

    // Scopes

    /**
     * Filtrar por status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Filtrar por status de pagamento
     */
    public function scopePaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    /**
     * Filtrar por cliente
     */
    public function scopeClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Ordenar por data de encomenda descendente
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('order_date', 'desc');
    }
}
