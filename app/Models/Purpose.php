<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purpose extends Model
{
    protected $table = 'purpose';
    public $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['name','date','amount','remarks'];

    public function installmentRel()
    {
        return $this->hasMany(Installment::class,'purpose','id');
    }
}
