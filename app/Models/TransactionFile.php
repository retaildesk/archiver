<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionFile extends Model
{
    use HasFactory;
    protected $table = "transactions_files";
    protected $fillable = ["transaction_id","path","ext","name"];

    public function transaction(){
        return $this->hasOne(Transaction::class, 'id','transaction_id');
    }

}
