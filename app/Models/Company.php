<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'company_name',
        'users_id',
        'products_id',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];

    protected $casts = [
        'products_id'       => 'array'
    ];

}
