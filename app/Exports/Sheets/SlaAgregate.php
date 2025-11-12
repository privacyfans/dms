<?php

namespace App\Exports\Sheets;

use App\Models\TmpSlaAgregate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Sheet;
use DB;
class SlaAgregate implements FromCollection,WithTitle,WithHeadings,WithCustomStartCell, WithEvents
{
    public function startCell(): string
    {
        return 'A3';
    }

    public function registerEvents(): array {
       
        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;
                $sheet->mergeCells('A1:A3');
                $sheet->setCellValue('A1', "No");
                $sheet->setCellValue('A4', "a");
                $sheet->mergeCells('B1:B3');
                $sheet->setCellValue('B1', "Tanggal Transaksi");
                $sheet->setCellValue('B4', "b");
                $sheet->mergeCells('C1:Q1');
                $sheet->setCellValue('C1', "Batch Time Business Unit");
                $sheet->mergeCells('C2:D2');
                $sheet->setCellValue('C2', "08:00 sd 10:00 WIB");
                $sheet->setCellValue('C3', "∑ Aplikasi");
                $sheet->setCellValue('C4', "c");
                $sheet->setCellValue('D3', "∑ Waktu");
                $sheet->setCellValue('D4', "d");
                $sheet->mergeCells('E2:F2');
                $sheet->setCellValue('E2', "10:00 sd 12:00 WIB");
                $sheet->setCellValue('E3', "∑ Aplikasi");
                $sheet->setCellValue('E4', "e");
                $sheet->setCellValue('F3', "∑ Waktu");
                $sheet->setCellValue('F4', "f");
                $sheet->mergeCells('G2:H2');
                $sheet->setCellValue('G2', "12:00 sd 13:00 WIB");
                $sheet->setCellValue('G3', "∑ Aplikasi");
                $sheet->setCellValue('G4', "g");
                $sheet->setCellValue('H3', "∑ Waktu");
                $sheet->setCellValue('H4', "h");
                $sheet->mergeCells('I2:J2');
                $sheet->setCellValue('I2', "13:00 sd 15:00 WIB");
                $sheet->setCellValue('I3', "∑ Aplikasi");
                $sheet->setCellValue('I4', "i");
                $sheet->setCellValue('J3', "∑ Waktu");
                $sheet->setCellValue('J4', "j");
                $sheet->mergeCells('K2:L2');
                $sheet->setCellValue('K2', "15:00 sd 17:00 WIB");
                $sheet->setCellValue('K3', "∑ Aplikasi");
                $sheet->setCellValue('K4', "k");
                $sheet->setCellValue('L3', "∑ Waktu");
                $sheet->setCellValue('L4', "l");
                $sheet->mergeCells('M2:N2');
                $sheet->setCellValue('M2', "17:00 sd 19:00 WIB");
                $sheet->setCellValue('M3', "∑ Aplikasi");
                $sheet->setCellValue('M4', "m");
                $sheet->setCellValue('N3', "∑ Waktu");
                $sheet->setCellValue('N4', "n");
                $sheet->mergeCells('O2:P2');
                $sheet->setCellValue('O2', "> 19:00 WIB");
                $sheet->setCellValue('O3', "∑ Aplikasi");
                $sheet->setCellValue('O4', "o");
                $sheet->setCellValue('P3', "∑ Waktu");
                $sheet->setCellValue('P4', "p");
                $sheet->mergeCells('Q1:Q3');
                $sheet->setCellValue('Q1', "∑ Aplikasi pengajuan");
                $sheet->setCellValue('Q4', "q = c + e + g + i + k + m + o");
                $sheet->mergeCells('R1:R3');
                $sheet->setCellValue('R1', "∑ Waktu Pengajuan");
                $sheet->setCellValue('R4', "r = d + f + h + j + l + n + p");
                $sheet->mergeCells('S1:T2');
                $sheet->setCellValue('S1', "Frekuensi Kekurangan Kelengkapan Dokumen");
                $sheet->setCellValue('S3', "∑ frekuensi");
                $sheet->setCellValue('S4', "s");
                $sheet->setCellValue('T3', "∑ frekuensi waktu");
                $sheet->setCellValue('T4', "t");
                $sheet->mergeCells('U1:U3');
                $sheet->setCellValue('U1', "∑ Waktu Tunggu UB SPV - DCRM Reviewer");
                $sheet->setCellValue('U4', "u");
                $sheet->mergeCells('V1:V3');
                $sheet->setCellValue('V1', "∑ Waktu Pengerjaan DCRM Reviewer - DCRM Team Leader");
                $sheet->setCellValue('V4', "v");
                $sheet->mergeCells('W1:W3');
                $sheet->setCellValue('W1', "∑ Waktu Tunggu DCRM Reviewer - DCRM Team Leader ");
                $sheet->setCellValue('W4', "w");
                $sheet->mergeCells('X1:X3');
                $sheet->setCellValue('X1', "∑ Waktu Pengerjaan DCRM Reviewer - DCRM Team Leader");
                $sheet->setCellValue('X4', "x");
                $sheet->mergeCells('Y1:Y3');
                $sheet->setCellValue('Y1', "∑ Waktu Keseluruhan");
                $sheet->setCellValue('Y4', "y");
                $sheet->mergeCells('Z1:AE1');
                $sheet->setCellValue('Z1', "Rata-rata Estimasi Perhitungan SLA Harian");
                $sheet->mergeCells('Z2:Z3');
                $sheet->setCellValue('Z2', "Rata-rata Waktu per Batch Time UB");
                $sheet->setCellValue('Z4', "z = r / q");
                $sheet->mergeCells('AA2:AA3');
                $sheet->setCellValue('AA2', "Rata-rata Waktu per Pemenuhan Kekurangan Dokumen UB");
                $sheet->setCellValue('AA4', "aa = t / s");
                $sheet->mergeCells('AB2:AB3');
                $sheet->setCellValue('AB2', "Rata-rata Waktu Tunggu DCRM Reviewer per Pengajuan");
                $sheet->setCellValue('AB4', "ab = u / q");
                $sheet->mergeCells('AC2:AC3');
                $sheet->setCellValue('AC2', "Rata-rata Waktu Pengerjaan DCRM Reviewer per Pengajuan");
                $sheet->setCellValue('AC4', "ac = v / q");
                $sheet->mergeCells('AD2:AD3');
                $sheet->setCellValue('AD2', "Rata-rata Waktu Tunggu DCRM Team Leader per Pengajuan");
                $sheet->setCellValue('AD4', "ad = w / q");
                $sheet->mergeCells('AE2:AE3');
                $sheet->setCellValue('AE2', "Rata-rata Waktu Pengerjaan DCRM Team Leader per Pengajuan");
                $sheet->setCellValue('AE4', "ae = x / q");
                $sheet->mergeCells('AF1:AF3');
                $sheet->setCellValue('AF1', "Rata-rata Waktu Pengerjaan per Loan ID per Hari (Estimasi SLA harian)");
                $sheet->setCellValue('AF4', "af = z + aa + ab + ac + ad + ae");
                $sheet->mergeCells('AG1:AG3');
                $sheet->setCellValue('AG1', "Rata-rata Waktu Pengerjaan per Loan ID per Hari (Estimasi SLA harian tanpa memperhitungkan pemenuhan kekurangan dokumen / kondisi ideal)");
                $sheet->setCellValue('AG4', "ag = ab + ac + ad + ae");
                $sheet->mergeCells('AH1:AH3');
                $sheet->setCellValue('AH1', "Rata-rata Waktu Pengerjaan per Loan ID per Hari (Estimasi SLA Dept. DCRM (Reviewer + Team Leader))");
                $sheet->setCellValue('AH4', "ah = ac + ae");
                $sheet->mergeCells('AI1:AI3');
                $sheet->setCellValue('AI1', "Waktu Terlama  Pengajuan Dokumen dalam 1 hari");
                $sheet->setCellValue('AI4', "ai");
                $sheet->mergeCells('AJ1:AJ3');
                $sheet->setCellValue('AJ1', "Waktu Tercepat  Pengajuan Dokumen dalam 1 hari");
                $sheet->setCellValue('AJ4', "aj");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:AJ4'; // All headers
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
           ['#', 'date'],
           ['Amount', 'Status'],
        ];
    }
    public function collection()
    {
        //return TmpSlaAgregate::all();
        $sql="select * from temp_sla_agregate";
        $result=DB::select($sql);
        return collect($result);
    }

    public function title(): string
    {
        return 'SlaAgregate';
    }

    
}
