<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'poster_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poster()
    {
        return $this->belongsTo(Poster::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}