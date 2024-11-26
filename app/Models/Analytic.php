<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    protected $fillable = [
        'event_type',
        'url',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}