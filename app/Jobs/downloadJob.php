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

class downloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $year;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($year)
    {
        $this->year = $year;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $datas = [
            "01"=>[],
            "02"=>[],
            "03"=>[],
            "04"=>[],
            "05"=>[],
            "06"=>[],
            "07"=>[],
            "08"=>[],
            "09"=>[],
            "10"=>[],
            "11"=>[],
            "12"=>[],
        ];
        foreach($datas as $key => $data){
            $start_date = Carbon::parse($this->year."-".$key."-01")->startOfMonth();
            $end_date = Carbon::parse($this->year."-".$key."-01")->endOfMonth();
            $transactions = Transaction::with("files")->has("files")->whereBetween('date',[$start_date,$end_date])->get();

            foreach($transactions as $transaction){
                $filenr = 1;
                foreach($transaction->files as $file){
                    $datas[$key][] = [
                        "id"=>$file->id,
                        "org_filename"=>$file->path,
                        "filename"=>str_replace("/","_",$transaction->date->format("d-m")."-".$transaction->name."-".$filenr.".pdf")
                    ];
                    $filenr++;
                }
            }
        }
        $zip = new \ZipArchive();
        $zip_name = "download-".$this->year.".zip";
        $zip_file = storage_path("app/zip/".$zip_name);
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $file_names = [];
        foreach($datas as $month => $files){
            foreach($files as $file){
                $tmp_file = storage_path("app/zip/".md5($file['filename']));
                $file_names[] = $tmp_file;
                file_put_contents($tmp_file,Storage::get($file['org_filename']));
                $zip->addFile($tmp_file, "/".$month."/".$file['filename']);
            }
        }
        $zip->close();
        Storage::put("zip/".$zip_name,file_get_contents($zip_file));

        unlink($zip_file);
        foreach($file_names as $file){
            unlink($file);
        }

    }
}
