<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ["iban","name","description","amount","date","finished","comment"];
    protected $dates  = ["date"];

    public function files(){
        return $this->hasMany(TransactionFile::class, 'transaction_id','id');
    }
}
