<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\InformNewStudents;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SingleController extends Controller
{
    //
    public function upload(Request $request){
        
        //validate the input
        $request->validate([
            'file' => 'required|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:2048', // max 2MB file
        ]);

        // Load the file
        $filePath = $request->file('file')->getRealPath();
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Get all emails from the first column and extra data from the second column
        $eligible_emails = [];
        $non_emails = [];
        foreach ($sheet->getRowIterator() as $row) {
            $cellA = $sheet->getCell('A' . $row->getRowIndex());
            $email = $cellA->getValue();
            $cellB = $sheet->getCell('B' . $row->getRowIndex());
            $additionalData = $cellB->getValue();

            // Validate email format
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $eligible_emails[] = [
                    'email' => $email,
                    'additionalData' =>$additionalData
                ];
                                    
            }
            else{
                $non_emails[]=$email;
            }
        }

        // Count the number of valid emails
        $emailCount = count($eligible_emails);
        session()->put('emails', $eligible_emails);
        return redirect()->route('confirm')
                        ->with('emailCount',$emailCount)
                        ->with('non_emails',$non_emails);
        
    }  
    
    public function send(Request $request){
        ini_set('max_execution_time', 300); // Set max execution time to 300 seconds
        $emails = session('emails');
        $errors =0;
        foreach($emails as $email){
            $error=0;
            try{
                Mail::to($email['email'])->send(new InformNewStudents($email['additionalData']));
            }
            catch(\Exception $e){
                Log::channel('mails_not_sent')->error('mail not sent to '.$email['email']);
                $error = 1;
                $errors= 1;
            }
            if(!$error)Log::channel('mails_sent')->info('mail sent to '.$email['email']);        
        }
        session()->forget('emails');
        if(!$errors)
            return redirect(url('/'))->with('success', 'Η ενέργεια ολοκληρώθηκε χωρίς λάθη');
        else
            return redirect(url('/'))->with('warning', 'Η ενέργεια ολοκληρώθηκε με λάθη που καταγράφηκαν στο log mails_not_sent ');
    }
}
