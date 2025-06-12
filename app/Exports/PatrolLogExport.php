<?php

namespace App\Exports;

use App\Models\PatrolLogModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class PatrolLogExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithTitle, WithCustomStartCell
{
    protected $logs;
    protected $bulan;
    protected $showKantor;

    public function __construct($bulan = null, $kantor = null)
    {
        $this->bulan = $bulan;
        $this->showKantor = in_array(auth()->user()->role, [0, 1]);

        $query = PatrolLogModel::with(['karyawan', 'kant', 'checkpoint']);

        if (auth()->user()->role == 0) {
            // Admin pusat: tidak ada filter perusahaan atau kantor
        } elseif (auth()->user()->role == 1) {
            $query->where('perusahaan', auth()->user()->perusahaan);

            if ($kantor) {
                $query->where('kantor', $kantor);
            }
        } elseif (auth()->user()->role == 3) {
            $query->where('perusahaan', auth()->user()->perusahaan)
                  ->where('kantor', auth()->user()->kantor);
        }

        if ($bulan) {
            $query->where('tgl_patrol', 'like', '%' . $bulan . '%');
        }

        $this->logs = $query->latest()->get();
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'Petugas',
            'Shift',
            'Lokasi',
            'Area',
            'Waktu',
            'Keterangan'
        ];

        if ($this->showKantor) {
            array_splice($headings, 1, 0, ['Kantor']);
        }

        return $headings;
    }

    public function collection()
    {
        return collect($this->logs)->map(function ($log, $index) {
            $row = [
                $index + 1,
                $log->karyawan->nama_lengkap ?? '-',
                $log->shift,
                $log->checkpoint->nama ?? '-',
                $log->checkpoint->lokasi ?? '-',
                $log->waktu_scan,
                $log->keterangan
            ];

            if ($this->showKantor) {
                array_splice($row, 1, 0, [$log->kant->nama_kantor ?? '-']);
            }

            return $row;
        });
    }

    public function styles(Worksheet $sheet)
    {
        $periode = Carbon::parse($this->bulan . '-01')->isoFormat('MMMM YYYY');
        $lastColumn = $this->showKantor ? 'H' : 'G';

        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', 'Rekap Patroli Periode ' . $periode);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [
            "A2:{$lastColumn}2" => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $rowCount = count($this->logs) + 2;
                $lastColumn = $this->showKantor ? 'H' : 'G';

                $event->sheet->getStyle("A2:{$lastColumn}{$rowCount}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'wrapText' => false
                    ]
                ]);

                foreach (range('A', $lastColumn) as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }

                // Set alignment center untuk kolom "No" termasuk heading
                $event->sheet->getDelegate()->getStyle('A2:A' . $rowCount)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A2:A' . $rowCount)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // Heading full center
                $event->sheet->getDelegate()->getStyle("A2:{$lastColumn}2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(25);
            }
        ];
    }

    public function title(): string
    {
        return 'Rekap Patroli';
    }
}
