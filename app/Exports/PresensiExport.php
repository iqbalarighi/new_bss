<?php 

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresensiExport implements FromView, WithStyles, WithEvents, WithColumnWidths
{
    protected $absen, $pegawai, $periode;

    public function __construct($absen, $pegawai, $periode)
    {
        $this->absen = $absen;
        $this->pegawai = $pegawai;
        $this->periode = $periode;
    }

    public function view(): View
    {
        return view('pegawai.excelpreview', [
            'absen' => $this->absen,
            'pegawai' => $this->pegawai,
            'periode' => $this->periode,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Judul tabel (row 12, misalnya)
            'A13:H13' => [
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
            'D' => 10,
            'E' => 15,
            'F' => 10,
            'G' => 30,
            'H' => 25,
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

public function drawings()
{
    $drawings = [];

    foreach ($this->absen as $index => $a) {
        $row = $index + 2; // karena header ada di baris 1

        // Foto Masuk
        if ($a->foto_in && file_exists(public_path('storage/absensi/' . $a->pegawai->nip . '/' . $a->foto_in))) {
            $drawingMasuk = new Drawing();
            $drawingMasuk->setName('Foto Masuk');
            $drawingMasuk->setPath(public_path('storage/absensi/' . $a->pegawai->nip . '/' . $a->foto_in));
            $drawingMasuk->setHeight(50); // Menentukan tinggi gambar
            $drawingMasuk->setWidth(50);  // Menentukan lebar gambar
            $drawingMasuk->setCoordinates('D' . $row);
            $drawingMasuk->setOffsetX(5); // Menyesuaikan sedikit offset agar lebih centering
            $drawingMasuk->setOffsetY(5); // Menyesuaikan sedikit offset agar lebih centering
            $drawings[] = $drawingMasuk;
        }

        // Foto Pulang
        if ($a->foto_out && file_exists(public_path('storage/absensi/' . $a->pegawai->nip . '/' . $a->foto_out))) {
            $drawingPulang = new Drawing();
            $drawingPulang->setName('Foto Pulang');
            $drawingPulang->setPath(public_path('storage/absensi/' . $a->pegawai->nip . '/' . $a->foto_out));
            $drawingPulang->setHeight(50); // Menentukan tinggi gambar
            $drawingPulang->setWidth(50);  // Menentukan lebar gambar
            $drawingPulang->setCoordinates('F' . $row);
            $drawingPulang->setOffsetX(5); // Menyesuaikan sedikit offset agar lebih centering
            $drawingPulang->setOffsetY(5); // Menyesuaikan sedikit offset agar lebih centering
            $drawings[] = $drawingPulang;
        }
    }

    return $drawings;
}



}
