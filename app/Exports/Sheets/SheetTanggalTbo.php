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
class SheetTanggalTbo implements FromCollection,WithTitle,WithHeadings, WithCustomStartCell, WithEvents
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
                
                $cellRange = 'A1:X1'; // All headers
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
            'Kode Kantor',
            'Nama Kantor',
            'User Input',
            'Nama User',
            'Tgl Input',
            'User Spv',
            'Nama Spv',
            'Flag Spv',
            'Date Flag Spv',
            'Note Spv',
            'NIK Kadep',
            'Nama Kadep',
            'Flag Kadep',
            'Date Flag Kadep',
            'Note Kadep',
            'NIK Kadiv',
            'Nama Kadiv',
            'Flag Kadiv',
            'Date Flag Kadiv',
            'Note Kadiv',
            'Final Flag',
            'Created Date',
            'Updated Date'
            ],
        ];
    }


    public function collection()
    {
        $sql='SELECT
        perubahan_tgl_tbo.id,
        perubahan_tgl_tbo.branch_input,
        branchlist.branch_name,
        perubahan_tgl_tbo.user_input,
        t_user_input.`name` AS user_input_name,
        perubahan_tgl_tbo.date_input,
        perubahan_tgl_tbo.user_spv,
        t_user_spv.`name` AS user_spv_name,
    CASE
            
            WHEN perubahan_tgl_tbo.flag_spv = 0 THEN
            "Pending" 
            WHEN perubahan_tgl_tbo.flag_spv = 1 THEN
            "Approve" ELSE "Reject" 
        END AS flag_spv,
        perubahan_tgl_tbo.date_flag_spv,
        perubahan_tgl_tbo.note_spv,
        perubahan_tgl_tbo.user_spv1,
        t_user_spv1.`name` AS user_kadep_name,
    CASE
            
            WHEN perubahan_tgl_tbo.flag_spv1 = 0 THEN
            "Pending" 
            WHEN perubahan_tgl_tbo.flag_spv1 = 1 THEN
            "Approve" ELSE "Reject" 
        END AS flag_spv1,
        perubahan_tgl_tbo.date_flag_spv1,
        perubahan_tgl_tbo.note_spv1,
        perubahan_tgl_tbo.user_spv2,
        t_user_spv2.`name` AS user_kadiv_name,
    CASE
            
            WHEN perubahan_tgl_tbo.flag_spv2 = 0 THEN
            "Pending" 
            WHEN perubahan_tgl_tbo.flag_spv2 = 1 THEN
            "Approve" ELSE "Reject" 
        END AS flag_spv2,
        perubahan_tgl_tbo.date_flag_spv2,
        perubahan_tgl_tbo.note_spv2,
    CASE
            
            WHEN perubahan_tgl_tbo.final_flag = 0 THEN
            "Pending" 
            WHEN perubahan_tgl_tbo.final_flag = 1 THEN
            "Approve" ELSE "Reject" 
        END AS final_flag,
        perubahan_tgl_tbo.created_at,
        perubahan_tgl_tbo.updated_at 
    FROM
        perubahan_tgl_tbo
        LEFT JOIN branchlist ON perubahan_tgl_tbo.branch_input = branchlist.branch_code
        LEFT JOIN users AS t_user_input ON perubahan_tgl_tbo.user_input = t_user_input.nik
        LEFT JOIN users AS t_user_spv ON perubahan_tgl_tbo.user_spv = t_user_spv.nik
        LEFT JOIN users AS t_user_spv1 ON perubahan_tgl_tbo.user_spv1 = t_user_spv1.nik
        LEFT JOIN users AS t_user_spv2 ON perubahan_tgl_tbo.user_spv2 = t_user_spv2.nik
        where date(date_input) >="'.$this->dari.'" and date(date_input) <= "'.$this->sampai.'"';

        $result=DB::select($sql);
        return collect($result);
    }

    public function title(): string
    {
        return 'Report Perpanjangan Tanggal';
    }

}    

