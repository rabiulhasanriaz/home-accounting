<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utility extends Model
{
    protected $table = 'utility';
    public $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['spender', 'purpose' ,'date', 'amount', 'remarks'];
}
