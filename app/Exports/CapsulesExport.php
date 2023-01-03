<?php

namespace App\Exports;

use App\Lesson;

use App\Exports\Sheets\CapsulesSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CapsulesExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $sheets = [];

        $lessons = Lesson::all();

        foreach($lessons as $lesson) {
            $sheets[] = new CapsulesSheet($lesson);
        }

        return $sheets;
    }
}
