<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenrePoster extends Model
{
    use HasFactory;
    protected $table = 'genre_poster'; // Указываем имя таблицы

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
