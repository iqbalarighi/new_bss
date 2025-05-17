<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LemburExport implements FromView, WithStyles, WithEvents, WithColumnWidths
{
    protected $lembur;
    protected $periode;


    public function __construct($lembur, $pegawai, $periode)
    {
        $this->lembur = $lembur;
        $this->pegawai = $pegawai;
        $this->periode = $periode;
    }

    public function view(): View
    {
        return view('pegawai.excellem', [
            'lembur' => $this->lembur,
            'pegawai' => $this->pegawai,
            'periode' => $this->periode,
        ]);
    }

public function styles(Worksheet $sheet)
    {
        return [
            // Judul tabel (row 12, misalnya)
            'A13:I13' => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 15,
            'C' => 15,
            'D' => 5,
            'E' => 15,
            'F' => 5,
            'G' => 30,
            'H' => 25,
            'I' => 25,
        ];
    }

public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();

            // Hitung baris terakhir secara dinamis
            $highestRow = $sheet->getHighestDataRow();
            $range = 'A13:I' . $highestRow;

            // Terapkan border dan alignment ke seluruh data mulai baris ke-15
            $sheet->getStyle($range)->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
            ]);
        },
    ];
}
}
