<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $table = 'installment';
    public $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['paidBy', 'purpose', 'date', 'amount', 'remarks'];

    public function purposeRel()
    {
        return $this->belongsTo(Purpose::class,'purpose','id');
    }
}
