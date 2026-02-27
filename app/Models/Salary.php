<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $table = 'salary';
    public $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['user', 'company' ,'date', 'amount'];
}
