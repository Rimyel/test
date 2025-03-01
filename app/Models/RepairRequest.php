<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairRequest extends Model
{
    protected $fillable = [
        'user_id',
        'model',
        'phone',
        'description',
        'status'
    ];
    const STATUSES = [
        'Новая' => 'Новая',
        'В работе' => 'В работе',
        'Завершена' => 'Завершена'
    ];
    
    public function getStatusOptions()
    {
        return self::STATUSES;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
