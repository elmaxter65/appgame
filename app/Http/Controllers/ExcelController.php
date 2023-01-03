<?php

namespace App\Http\Controllers;

use Throwable;
use ZipArchive;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\GeneralExport;
use App\Exports\CapsulesExport;
use App\Exports\QuestionExport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ExcelController extends Controller
{
    public function export($password)
    {
        
        $name = Carbon::now()->format('dmy');
        $zip = new ZipArchive;
        
        if ($zip->open('data'.$name.'.zip', ZipArchive::CREATE) === true) {
            $excel_general = Excel::download(new GeneralExport, 'general.xlsx')->getFile();
            $zip->addFile($excel_general,'general'.$name.'.xlsx');
            

            $excel_capsules = Excel::download(new CapsulesExport, 'capsules.xlsx')->getFile();
            $zip->addFile($excel_capsules,
'capsules'.$name.'.xlsx');
            

            $excel_questions = Excel::download(new QuestionExport, 'questions.xlsx')->getFile();
            $zip->addFile($excel_questions,
'questions'.$name.'.xlsx');
            

            $zip->setEncryptionName('general'.$name.'.xlsx', ZipArchive::EM_AES_256, $password);
            $zip->close();
        }

        
        
        return response()->download('data'.$name.'.zip')->deleteFileAfterSend(true);
    }

    public function downloadExcel(Request $request){

        $this->cleanDirectory();

        $validator = Validator::make($request->all(), [
            'excel_password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->getMessageBag()->toArray());
        }

        $password = $request->get('excel_password');
    
      
        return $this->export($password);

    }

    public function emailExcel(Request $request){

        $this->cleanDirectory();

        $validator = Validator::make($request->all(), [
            'email_excel' => 'required|email',
            'excel_send_password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->getMessageBag()->toArray());
        }

        $password = $request->get('excel_password');

        // create file
        
        $name = Carbon::now()->format('dmy');
        $zip = new ZipArchive;
        if ($zip->open('data'.$name.'.zip', ZipArchive::CREATE) === true) {
            $zip->addFile(Excel::download(new GeneralExport, 'general.xlsx')->getFile(),
                'general'.$name.'.xlsx');
            $zip->addFile(Excel::download(new CapsulesExport, 'capsules.xlsx')->getFile(),
                'capsules'.$name.'.xlsx');
            $zip->addFile(Excel::download(new QuestionExport, 'questions.xlsx')->getFile(),
                'questions'.$name.'.xlsx');
            $zip->setEncryptionName('general'.$name.'.xlsx', ZipArchive::EM_AES_256, $password);
            $zip->close();
        }

        // send email
        $email = $request->get('email_excel');
        $data = array( 'email' =>$email, "name" =>$name);
        
        try {
            Mail::send('emails.excel', $data, function($message) use ($data)
        {
            $message->to($data['email'])->subject('OCT App Statistics');
            $message->attach(public_path('data'.$data['name'].'.zip'), [
                'as' => 'data'.$data['name'].'.zip',
                'mime' => 'application/zip',
           ]);
            
        });
        } catch (Throwable $e) {
            return redirect()->back()->with('msg_ko',"There has been an error");
        }

        return redirect()->back()->with('msg_ok' ,"Email sent");


    }


    public function cleanDirectory(){
        return File::cleanDirectory(storage_path().'/framework/laravel-excel');
        
    }
}
