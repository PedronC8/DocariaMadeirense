<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientModel extends Model
{
    //

    //
    protected $table = 'clients';

    protected $fillable = [
    'id',
    'id_faturacao',
    'name',
    'nif',
    'contact',
    'address'
];

}
