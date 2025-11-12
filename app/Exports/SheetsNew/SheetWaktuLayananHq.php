<?php

namespace App\Exports\Sheets;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Sheet;
use DB;
class SheetWaktuLayananHq implements FromCollection,WithTitle,WithHeadings, WithCustomStartCell, WithEvents
{

    protected $dari;
    protected $sampai;

    function __construct($dari,$sampai) {
            $this->dari = $dari;
            $this->sampai = $sampai;
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function registerEvents(): array {
       
        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;
                /*$sheet->mergeCells('A1:A2');
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
                $sheet->mergeCells('K1:O1');
                $sheet->setCellValue('K1', "Batch Time Business Unit");
                $sheet->setCellValue('K2', "08:00 sd 10:00 WIB");
                $sheet->setCellValue('L2', "10:00 sd 12:00 WIB");
                $sheet->setCellValue('M2', "13:00 sd 15:00 WIB");
                $sheet->setCellValue('N2', "15:00 sd 17:00 WIB");
                $sheet->setCellValue('O2', "> 17:00 WIB");
                $sheet->mergeCells('P1:Q1');
                $sheet->setCellValue('P1', "Frekuensi Kekurangan Kelengkapan Dokumen");
                $sheet->setCellValue('P2', "∑ frekuensi");
                $sheet->setCellValue('Q2', "∑ waktu");
                $sheet->mergeCells('R1:R2');
                $sheet->setCellValue('R1', "∑ Waktu UB SPV - BPR Lev 1");
                $sheet->mergeCells('S1:S2');
                $sheet->setCellValue('S1', "∑ Waktu BPR Lev 1 - BPR Lev 2");
                $sheet->mergeCells('T1:T2');
                $sheet->setCellValue('T1', "∑ Waktu");*/
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:J1'; // All headers
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
            ['ID',
            'User Input',
            'Nama User',
            'Tgl Input',
            'Jam Layanan Yang Diajukan',
            'NIK Kadiv',
            'Nama Kadiv',
            'Date Flag Kadiv',
            'Final Flag',
            'Jam Layanan Approve',
            
            ],
        ];
    }


    public function collection()
    {
        $sql='SELECT
        cut_off_hq_history.id,
        cut_off_hq_history.user_input,
        t_user_input.`name` as name_user_input,
        cut_off_hq_history.date_input,
        cut_off_hq_history.purpose_cut_off,
        cut_off_hq_history.user_approve,
        t_user_approve.`name` as name_user_approve,
        cut_off_hq_history.approve_date,
        CASE
                            WHEN cut_off_hq_history.approve_flag = 0 THEN "Pending"
                            WHEN cut_off_hq_history.approve_flag = 1 THEN "Approve"
                            ELSE "Reject"
                    END as approve_flag,
        cut_off_hq_history.cut_off 
    FROM
        cut_off_hq_history
        LEFT JOIN users AS t_user_input ON cut_off_hq_history.user_input = t_user_input.nik
        LEFT JOIN users AS t_user_approve ON cut_off_hq_history.user_approve = t_user_approve.nik
        where date(date_input) >="'.$this->dari.'" and date(date_input) <= "'.$this->sampai.'"';

        $result=DB::select($sql);
        return collect($result);
    }

    public function title(): string
    {
    return 'Perpanjangan Waktu Layanan HQ';
    }

}    

