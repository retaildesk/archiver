<?php

namespace App\Http\Controllers;


use App\Models\TransactionFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Overheid\Exception;
use View;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Response;
use Carbon\Carbon;

class BrowserController extends Controller
{
    //todo
    //bestand moet weg als deze al keer is toegevoegd
    //comment glitch

    public function post(Request $request){
        $start = substr($request->start,0,10);
        $end = substr($request->end,0,10);
        $q = $request->q;
        if($request->type == "file"){
            return $this->getFtpFiles($start,$end,$q);
        } else {
            return $this->getGmailAttachments($q);
        }
    }
    private function getFtpFiles($start,$end,$q){
        $start = Carbon::parse($start)->startOfDay()->timestamp;
        $end = Carbon::parse($end)->endOfDay()->timestamp;
        $files = [];
        $all_files = Storage::disk('ftp')->listContents("scans");


        foreach($all_files as $file){
            if(
                ((empty($q) || (str_contains(strtolower($file['basename']),strtolower($q)))))
                && TransactionFile::where('name',$file['basename'])->count() === 0
            ){
                $files[] = [
                    "from"=>["email"=>"-","name"=>"scan"],
                    "filename"=>$file['basename'],
                    "date"=>Carbon::createFromTimestamp($file['timestamp']),
                    "url"=>$this->getUrl("scan",[
                        "filename"=>$file['basename']
                    ]),
                    "connect"=>base64_encode(json_encode([
                        "type"=>"scan",
                        "filename"=>$file['basename'],
                    ])),
                ];
            }
        }

        return $files;
    }
    private function getGmailAttachments($q){
        $query = "";
        if(!empty($q)){
            $query = $q;
        }
        $attachments = [];
        $count = 0;
        try{
            $messages = LaravelGmail::message()->raw("in:INBOX filename:pdf has:attachment ".$query)->preload()->all();

            foreach ( $messages as $message ) {
                $attachment = $this->doMail($message);
                if($attachment){
                    $attachments[] = $attachment;
                }
            }
            $next = $messages->next();
            while($next != false && $count < 5){
                $count ++;
                foreach ( $next as $message ) {
                    $attachment = $this->doMail($message);
                    if($attachment){
                        $attachments[] = $attachment;
                    }
                }
            }
        } catch(\Exception $e){

        }
        return $attachments;
    }
    private function doMail($message){
        $mail_id = $message->getId();
        $from = $message->getFrom();
        $date = $message->getDate();
        $data = false;
        foreach($message->getAttachments() as $attachment){

            if(
                !str_contains(strtolower($attachment->getFilename()),"offerte")
            ){
                $data = [
                    "from"=>$from,
                    "filename"=>$attachment->getFilename(),
                    "date"=>$date,
                    "connect"=>base64_encode(json_encode([
                        "type"=>"attachment",
                        "filename"=>$attachment->getFilename(),
                        "mail_id"=>$mail_id,
                    ])),
                    "url"=>$this->getUrl("attachment",[
                        "mail_id"=>$mail_id,
                        "filename"=>$attachment->getFilename()
                    ])
                ];
            }
        }
        return $data;
    }
    private function getGmailAttachment($id,$get_file = true){
        $message = LaravelGmail::message()->get($id->mail_id);
        foreach($message->getAttachments() as $attachment){
            if($attachment->getFilename() == $id->filename){
                if($get_file){
                    return base64_decode(strtr($attachment->getData(), array('-' => '+', '_' => '/')));
                } else {
                    return $attachment;
                }
            }
        }
        return false;
    }
    private function getUrl($type,$data){
        if($type == "attachment"){
            return env("APP_URL")."/browser/open/attachment/".base64_encode(json_encode($data));
        }
        if($type == "scan"){
            return env("APP_URL")."/browser/open/scan/".base64_encode(json_encode($data));
        }
    }
    public function openAttachment(Request $request){
        if($request->type == "attachment"){
            $image = json_decode(base64_decode($request->base64));
            $file = $this->getGmailAttachment($image);
        }
        if($request->type == "scan"){
            $image = json_decode(base64_decode($request->base64));
            $file = Storage::get("scans/".$image->filename);
        }
        if($file){
            return Response::make($file, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$image->filename.'"'
            ]);
        }
        abort(404);
    }

    public function connectAttachment(Request $request){
        $image = json_decode(base64_decode($request->base64));
        $time = time();
        $new_filename = md5($time.$image->filename);
        if($image->type == "attachment"){
            $file = $this->getGmailAttachment($image,false);
            $file->saveAttachmentTo("files", $new_filename, "ftp");
        }
        if($image->type == "scan"){
            Storage::copy('scans/'.$image->filename, "files/".$new_filename);
        }

        TransactionFile::create([
            "path"=>"files/".$new_filename,
            "transaction_id"=>$request->transaction_id,
            "ext"=>"pdf",
            "name"=>$image->filename
        ]);
    }

}
