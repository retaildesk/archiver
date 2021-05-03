<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;


use Illuminate\Support\Facades\Storage;

class processMT940Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parser = new \Kingsquare\Parser\Banking\Mt940();
        $parsedStatements = $parser->parse(Storage::get($this->file));
        foreach($parsedStatements[0]->getTransactions() as $transaction){
            if($transaction->getDebitCredit() == "D"){
                $desc = $transaction->getDescription();
                $iban = $transaction->getAccount();
                $name = $transaction->getAccountName();
                $date = Carbon::createFromTimestamp($transaction->getValueTimestamp());
                $amount = $transaction->getPrice()*100;
                $exists = Transaction::where("description",$desc)->where('iban',$iban)->where("date",$date)->where("amount",$amount)->where("name",$name)->first();
                if(!$exists){
                    if(!in_array(strtolower($name),["belastingdienst","posten"])){
                        Transaction::create([
                            "iban"=>$iban,
                            "name"=>$name,
                            "description"=>$desc,
                            "amount"=>$amount,
                            "date"=>$date,
                        ]);
                    }
                }
            }
        }
    }
}
