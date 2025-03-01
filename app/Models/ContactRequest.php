<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    use HasFactory;


protected $fillable = [
    'user_id',
    'poster_id', 
    'phone'
];

public function poster()
{
    return $this->belongsTo(Poster::class, 'poster_id');
}
public function user()
{
    return $this->belongsTo(User::class);
}
}
