<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
    'article_id',
    'name',
    'comment',
    'is_approved',
    'is_active',
    ];

protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', 1);
        });
    }


    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    
  

    
    
}
