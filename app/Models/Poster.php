<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poster extends Model
{
    use HasFactory;

    // Поля, которые можно массово назначать
    protected $fillable = [
        'name',
        'description',
        'image',
        'visibility',
        'created_at'
    ];

    // Связь с лайками
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Связь с параметрами через промежуточную таблицу parameter_miner
    public function parameters()
    {
        return $this->belongsToMany(Parameter::class, 'parameter_miner', 'miner_id', 'parameter_id');
    }
}