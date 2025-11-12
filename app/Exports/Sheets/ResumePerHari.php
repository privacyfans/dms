<?php

namespace App\Exports\Sheets;

use App\Models\TmpResumePerhari;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Sheet;
use DB;
class ResumePerHari implements FromCollection,WithTitle,WithHeadings, WithCustomStartCell, WithEvents
{
    public function startCell(): string
    {
        return 'A2';
    }

    public function registerEvents(): array {
       
        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;
                $sheet->mergeCells('A1:A2');
                $sheet->setCellValue('A1', "No");
                $sheet->mergeCells('B1:B2');
                $sheet->setCellValue('B1', "Kode Kantor");
                $sheet->mergeCells('C1:C2');
                $sheet->setCellValue('C1', "Nama Kantor");
                $sheet->mergeCells('D1:D2');
                $sheet->setCellValue('D1', "Loan ID");
                $sheet->mergeCells('E1:E2');
                $sheet->setCellValue('E1', "Nama Debitur");
                $sheet->mergeCells('F1:F2');
                $sheet->setCellValue('F1', "Status");
                $sheet->mergeCells('G1:G2');
                $sheet->setCellValue('G1', "Tanggal Input");
                $sheet->mergeCells('H1:H2');
                $sheet->setCellValue('H1', "UB SPV");
                $sheet->mergeCells('I1:I2');
                $sheet->setCellValue('I1', "Level 1 in charge");
                $sheet->mergeCells('J1:J2');
                $sheet->setCellValue('J1', "Level 2 in charge");
                $sheet->mergeCells('K1:P1');
                $sheet->setCellValue('K1', "Batch Time Business Unit");
                $sheet->setCellValue('K2', "08:00 sd 10:00 WIB");
                $sheet->setCellValue('L2', "10:00 sd 12:00 WIB");
                $sheet->setCellValue('M2', "12:00 sd 13:00 WIB");
                $sheet->setCellValue('N2', "13:00 sd 15:00 WIB");
                $sheet->setCellValue('O2', "15:00 sd 17:00 WIB");
                $sheet->setCellValue('P2', "17:00 sd 19:00 WIB");
                $sheet->setCellValue('Q2', "> 19:00 WIB");
                $sheet->mergeCells('R1:S1');
                $sheet->setCellValue('R1', "Frekuensi Kekurangan Kelengkapan Dokumen");
                $sheet->setCellValue('R2', "∑ frekuensi");
                $sheet->setCellValue('S2', "∑ waktu");
                $sheet->mergeCells('T1:T2');
                $sheet->setCellValue('T1', "∑ Waktu Tunggu UB SPV - DCRM Reviewer");
                $sheet->mergeCells('U1:U2');
                $sheet->setCellValue('U1', "∑ Waktu Pengerjaan UB SPV - DCRM Reviewer");
                $sheet->mergeCells('V1:V2');
                $sheet->setCellValue('V1', "∑ Waktu Tunggu DCRM Reviewer - DCRM Team Leader");
                $sheet->mergeCells('W1:W2');
                $sheet->setCellValue('W1', "∑ Waktu Pengerjaan DCRM Reviewer - DCRM Team Leader");
                $sheet->mergeCells('X1:X2');
                $sheet->setCellValue('X1', "∑ Waktu");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:X2'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);

                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ])->getAlignment()->setWrapText(true);
            },
        ];
    }
    public function headings(): array
    {
        return [
            ['No',
            'Kode Kantor',
            'Nama Kantor',
            'Loan ID',
            'Nama Debitur',
            'Status',
            'Tanggal Input',
            'UB SPV',
            'Level 1 in charge',
            'Level 2 in charge',
            '08:00 sd 10:00 WIB',
            '10:00 sd 12:00 WIB',
            '12:00 sd 13:00 WIB',
            '13:00 sd 15:00 WIB',
            '15:00 sd 17:00 WIB',
            '17:00 sd 19:00 WIB',
            '> 19:00 WIB',
            '∑ frekuensi',
            '∑ waktu ',
            '∑ Waktu Tunggu UB SPV - DCRM Reviwer',
            '∑ Waktu Pengerjaan UB SPV - DCRM Reviwer',
            '∑ Waktu Tunggu DCRM Reviewer - DCRM Team Leader',
            '∑ Waktu Pengerjaan DCRM Reviewer - DCRM Team Leader',
            '∑ Waktu'],
        ];
    }


    public function collection()
    {
       // return TmpResumePerhari::all();
       $sql="select * from temp_resume_perhari";
       $result=DB::select($sql);
       return collect($result);
    }

    public function title(): string
    {
        return 'ResumePerhari';
    }

}    

