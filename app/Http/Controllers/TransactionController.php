<?php

namespace App\Http\Controllers;

use App\DataTables\TransactionsDataTable;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use View;

class TransactionController extends Controller
{
    public function get(Request $request){
        $transaction = Transaction::with('files')->find($request->id);
        return ["status"=>200,"transaction"=>$transaction];
    }
    public function comment(Request $request){
        $transaction = Transaction::find($request->id);
        if($transaction){
            $transaction->comment = $request->comment;
            $transaction->save();
        }
        return array("status"=>200);
    }
    public function status(Request $request){
        if($request->status == "open"){
            $finished = 0;
        }
        if($request->status == "close"){
            $finished = 1;
        }
        $transaction = Transaction::find($request->id);
        if($transaction){
            $transaction->finished =$finished;
            $transaction->save();
        }
    }
}
