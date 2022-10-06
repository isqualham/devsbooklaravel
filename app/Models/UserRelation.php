<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRelation extends Model
{
    protected $table = 'users_relations';
    public $timestamps = false;

    protected $fillable = [
        'user_fron',
        'user_to',
    ];
    
}
