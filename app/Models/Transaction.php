<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'amount',
        'payment_method',
        'payment_proof'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }
}