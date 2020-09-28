<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentLookup extends Model
{
    use HasFactory;
    protected $table = "agents_lookups";
    protected $fillable = [
        'id',
        'users_id',
        'companies_id',
        'created_at',
        'updated_at'
    ];
}
