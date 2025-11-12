<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\ResumePerHari;
use App\Exports\Sheets\SlaAgregate;

class Sla implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new ResumePerHari;
        $sheets[] = new SlaAgregate;
        return $sheets;
    }
}
