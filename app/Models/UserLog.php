<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $fillable= [
        'user_id',
        'ip',
        'user_agent',
        'url',
        'post_data',
    ];

}
