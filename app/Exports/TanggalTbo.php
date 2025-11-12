<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\SheetTanggalTbo;
use App\Exports\Sheets\SheetTanggalTboDetail;

class TanggalTbo implements WithMultipleSheets
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
        $sheets[] = new SheetTanggalTbo($this->dari,$this->sampai);
        $sheets[] = new SheetTanggalTboDetail($this->dari,$this->sampai);
        return $sheets;
    }
}
