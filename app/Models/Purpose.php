<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purpose extends Model
{
    protected $table = 'purpose';
    public $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['name','date','amount','remarks'];
}
