<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class RekapAbsensiExport implements FromView, WithEvents
{
    protected $rekap;
    protected $bulan;
    protected $tahun;
    protected $periode;
    protected $satker;
    protected $depar;
    protected $sat;
    protected $jumlahHari;

    public function __construct(
        $rekap, $bulan, $tahun,
        $periode, $satker, $depar, $sat, $jumlahHari
    ) {
        $this->rekap = $rekap;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->periode = $periode;
        $this->satker = $satker;
        $this->depar = $depar;
        $this->sat = $sat;
        $this->jumlahHari = $jumlahHari;
    }

    public function view(): View
    {
        return view('pegawai.excel', [
            'rekap' => $this->rekap,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'periode' => $this->periode,
            'satker' => $this->satker,
            'depar' => $this->depar,
            'sat' => $this->sat
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

                $sheet = $event->sheet->getDelegate();
                $startRow = (!empty($this->satker) ? 8 : 7);

                foreach ($this->rekap as $rowIndex => $r) {
                    $barisExcel = $startRow + $rowIndex;

                    for ($i = 1; $i <= $this->jumlahHari; $i++) {
                        $tgl = Carbon::createFromDate($this->tahun, $this->bulan, $i)->toDateString();
                        $absen = $r['absensi']->firstWhere('tgl_absen', $tgl);

                        if ($absen) {
                            $jamMasuk = $absen->jam_in ?? '';
                            $jamKeluar = $absen->jam_out ?? '00:00:00';
                            $shiftMasuk = $absen->shifts->jam_masuk;

                            $jamText = new RichText();

                            $textMasuk = $jamText->createTextRun($jamMasuk);
                            $textMasuk->getFont()->setColor(new Color(
                                $jamMasuk > $shiftMasuk ? Color::COLOR_RED : Color::COLOR_BLACK
                            ));

                            $jamText->createText("\n");

                            $textKeluar = $jamText->createTextRun($jamKeluar);
                            $textKeluar->getFont()->setColor(new Color(
                                $jamKeluar === '00:00:00' ? Color::COLOR_RED : Color::COLOR_BLACK
                            ));

                            $kolomIndex = ($this->satker == null ? 5 : 4) + $i - 1;
                            $kolomExcel = Coordinate::stringFromColumnIndex($kolomIndex);

                            $sheet->getCell("{$kolomExcel}{$barisExcel}")->setValue($jamText);
                            $sheet->getStyle("{$kolomExcel}{$barisExcel}")->getAlignment()->setWrapText(true);
                        }
                    }
                }
            }
        ];
    }
}
