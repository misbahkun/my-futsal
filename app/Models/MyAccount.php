<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyAccount extends Model
{
    protected $table = 'my_account';
    protected $fillable = ['name', 'account_name', 'account_number', 'type', 'image'];
}