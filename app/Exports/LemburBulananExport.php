<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class LemburBulananExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $rekap;
    protected $bulan;
    protected $tahun;
    protected $periode;
    protected $satker;
    protected $depar;
    protected $sat;
    protected $jumlahHari;

    public function __construct($rekap, $bulan, $tahun, $periode, $satker, $depar, $sat, $jumlahHari)
    {
        $this->rekap = $rekap;
        $this->depar = $depar;
        $this->sat = $sat;
        $this->satker = $satker;
        $this->periode = $periode;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->jumlahHari = $jumlahHari;
    }

    public function view(): View
    {
        return view('pegawai.excellembul', [
            'rekap' => $this->rekap,
            'depar' => $this->depar,
            'sat' => $this->sat,
            'satker' => $this->satker,
            'periode' => $this->periode,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'jumlahHari' => $this->jumlahHari,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $totalKolom = ($this->satker == null ? 4 : 3) + $this->jumlahHari + 1;
                $kolomAkhir = Coordinate::stringFromColumnIndex($totalKolom);

                $judulEndRow = ($this->satker != null ? 4 : 3);

                // Pastikan tidak merge lebih dari baris yang tersedia
                for ($i = 1; $i <= $judulEndRow; $i++) {
                    $event->sheet->mergeCells("A{$i}:{$kolomAkhir}{$i}");
                }

                $event->sheet->getStyle("A1:A{$judulEndRow}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ]
                ]);

                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $borderStartRow = (!empty($this->satker) ? 6 : 5);

                $event->sheet->getStyle("A{$borderStartRow}:{$kolomAkhir}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                for ($col = 1; $col <= $totalKolom; $col++) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $event->sheet->getDelegate()->getColumnDimension($colLetter)->setAutoSize(true);
                }
            },
        ];
    }
}

