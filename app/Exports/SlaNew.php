<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\SheetsNew\ResumePerHari;
use App\Exports\SheetsNew\SlaAgregate;

class SlaNew implements WithMultipleSheets
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
