<?php

namespace App\Http\Controllers;

use App\Jobs\downloadJob;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use View;

class DownloadController extends Controller
{
    public function form(){
        $start_year = "2021";
        $current_year = date("Y");
        $years = [$start_year];
        while($start_year != $current_year){
            $years[] = $start_year++;
        }
        return view('download',compact('years'));
    }

    public function startJob(Request $request){
        downloadJob::dispatch($request->year);
    }
}
