<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'updated_at',
        'created_at',
        'updated_by',
        'deleted_at'
    ];
}
