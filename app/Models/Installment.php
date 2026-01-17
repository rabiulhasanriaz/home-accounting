<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $table = 'installment';
    public $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['purpose', 'date', 'amount', 'remarks'];
}
