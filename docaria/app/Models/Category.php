<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';
    
    public $timestamps = false;
    
    protected $fillable = [
        'name',
    ];

    // Relacionamentos

    /**
     * Subcategorias desta categoria
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class, 'category_id');
    }
}
