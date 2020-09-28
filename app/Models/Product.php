<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;

    use softDeletes;

    protected $fillable = [
        'id', 'name', 'created_at', 'updated_at', 'updated_by', 'deleted_by'
    ];

    

}
