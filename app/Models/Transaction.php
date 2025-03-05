<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'schedule_id', 'amount', 'status', 'payment_method'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
