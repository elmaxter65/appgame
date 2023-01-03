<?php

namespace App\Exports;

use App\UserApp;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuestionExport implements FromView, WithStyles, WithTitle
{
    public function view(): View
    {
        return view('excel.question-worksheet', [
            'users' => $this->getData()
        ]);

    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:M1')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('4a61e2');

        $sheet->getStyle('A1:M1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        $sheet->getStyle('A1:M1')->getFont()->setBold(true);
        $sheet->getStyle('A1:M1')->getFont()->setSize(11);
        $sheet->getStyle('A1:M1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
    public function getData(){
        $data = new Collection();
        $users = UserApp::all();
        $i = 0;
        foreach($users as $user){
            $d = DB::table('oauth_access_tokens')->where('user_id', $user->id)->latest('created_at')->first();
            if($d != null){
                $last = $d->created_at;
            }else{
                continue;
            }
            $questions = $user->getGamesPlayedDetails();
            foreach($questions as $question){
                $user_name = ($user->name != null) ? decrypt($user->name) : "--";
                $user_city = ($user->city != null) ? decrypt($user->city) : "--";
                $user_country = ($user->country != null) ? decrypt($user->country) : "--";
                $user_ocupation = ($user->occupation != null) ? decrypt($user->occupation) : "--";
                $user_hospital = ($user->hospital != null) ? decrypt($user->hospital) : "--";
                $user_email = ($user->email != null) ? decrypt($user->email) : "--";
                $temp = [
                    'name' =>  $user_name,
                    'city' =>  $user_city,
                    'country' =>  $user_country,
                    'ocupation' =>  $user_ocupation,
                    'hospital' =>  $user_hospital,
                    'email' => $user_email,
                    'register' =>  $user->created_at,
                    'logIn' =>  $last,
                    'capsule' =>  $question['lesson_name'],
                    'question' =>  $question['question_name'],
                    'timeCorrect' =>  $question['times_right'],
                    'timeWrong' =>  $question['times_wrong'],
                    ];
                    $object = (object) $temp;
                    $data->add($object);
            }
            $i++;
        }
        return $data;
    }
    public function title(): string
    {
        return 'Questions';
    }
}
