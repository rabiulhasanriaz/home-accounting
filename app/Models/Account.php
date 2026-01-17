<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'account';
    public $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['spender', 'purpose' ,'date', 'amount', 'remarks'];
}
