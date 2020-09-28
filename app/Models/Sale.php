<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $table = "sales";
    protected $fillable = [
        'id',
        'companies_id',
        'users_id',
        'products_id',
        'amount',
        'updated_at',
        'created_at',
        'updated_by',
        'deleted_at'
    ];

}
