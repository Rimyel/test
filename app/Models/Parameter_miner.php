<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter_miner extends Model
{
    use HasFactory;
    protected $table = 'Parameter_miner'; // Указываем имя таблицы

    protected $fillable = [
        'genre_id',
        'poster_id',
        'created_at'
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function poster()
    {
        return $this->belongsTo(Poster::class);
    }
}
