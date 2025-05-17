<?php

namespace App\Exports;

use App\Models\LemburModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LemburExport implements FromView, WithStyles, WithEvents
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

public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();

            // Hitung baris terakhir secara dinamis
            $highestRow = $sheet->getHighestDataRow();
            $range = 'A13:H' . $highestRow;

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
