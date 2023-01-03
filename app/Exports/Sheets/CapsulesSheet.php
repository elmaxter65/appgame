<?php

namespace App\Exports\Sheets;

use App\UserApp;
use Carbon\Carbon;
use App\TimeinLesson;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CapsulesSheet implements WithTitle, FromView, WithStyles
{
    private $lesson;

    public function __construct($lesson)
    {
        $this->lesson = $lesson;
    }

    public function view(): View
    {
        return view('excel.capsules-worksheet', [
            'users' => $this->getData()
        ]);
    }

    public function getData(){
        $data = new Collection();
        $users = UserApp::all();
        foreach($users as $user){
            $d = DB::table('oauth_access_tokens')->where('user_id', $user->id)->latest('created_at')->first();
            if($d != null){
                $last = $d->created_at;
            }else{
                continue;
            }
            $time = Carbon::parse(CarbonInterval::seconds(TimeinLesson::where('user_id',$user->id)->where('lesson_id', $this->lesson->id)->sum('time_spent_sec')))->diffInMinutes(Carbon::now());
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
                'logIn' =>  $user->created_at,
                'time' =>  $time.' min',
                'questions' =>  $user->getGamesPlayedInLesson($this->lesson->id),
                ];
                $object = (object) $temp;
                $data->add($object);

        }
        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('4a61e2');

        $sheet->getStyle('A1:K1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getFont()->setSize(11);
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    public function title(): string
    {
        return 'Capsule: ' . $this->lesson->name;
    }
}

