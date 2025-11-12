<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\SheetWaktuLayanan;
use App\Exports\Sheets\SheetWaktuLayananHq;

class WaktuLayanan implements WithMultipleSheets
{
    protected $dari;
    protected $sampai;

    function __construct($dari,$sampai) {
            $this->dari = $dari;
            $this->sampai = $sampai;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new SheetWaktuLayanan($this->dari,$this->sampai);
        $sheets[] = new SheetWaktuLayananHq($this->dari,$this->sampai);
        return $sheets;
    }
}
