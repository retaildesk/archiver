<?php

namespace App\Http\Controllers;

use App\Jobs\processMT940Job;
use App\Models\TransactionFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function formMt940(){
        return view('formMt940');
    }
    public function Mt940Upload(Request $request){
        $file = $request->file('file');
        $FileName = $file->getClientOriginalName();
        $extention = $file->getClientOriginalExtension();
        if($extention != "940"){
            return Response::json('error', 400);
        }
        $stored_file_path = Storage::putFile('mt940', $file);
        processMT940Job::dispatch($stored_file_path);
        return response()->json(['success' => $FileName]);
    }
    public function TransactionUpload(Request $request){
        $file = $request->file('file');
        $FileName = $file->getClientOriginalName();
        $extention = $file->getClientOriginalExtension();
        if(in_array(strtolower($extention),["pdf","jpg","jpeg","png","gif","xls","xlsx","csv"])){
            $stored_file_path = Storage::putFile('files', $file);
            TransactionFile::create([
                "path"=>$stored_file_path,
                "transaction_id"=>$request->transaction_dropzone_id,
                "ext"=>$extention,
                "name"=>$FileName
            ]);
            return response()->json(['success' => $FileName]);
        } else {
            return response()->json(['error' => 400]);
        }
    }
    public function getFile(Request $request){
        $file = TransactionFile::find($request->id);
        return Storage::download($file->path,$file->name);
    }
    public function deleteFile(Request $request){
        $file = TransactionFile::find($request->id);
        Storage::delete($file->path);
        $file->delete();
        return response()->json(['success' => 200]);
    }
}
