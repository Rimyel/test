<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute',
        'name'
];

    public function posters()
    {
        return $this->belongsToMany(Poster::class);
    }
}
