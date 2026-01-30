<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use App\Models\TukarjagaModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TukarjagaExport implements FromView, ShouldAutoSize, WithStyles
{

    public function __construct($start , $end)
    {
        $this->start = $start;
        $this->end = $end;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

    }

public function view(): View
    {  
        $level = Auth::user()->level;
        $gd = Auth::user()->lokasi_tugas;

       $start = $this->start;
       $end = $this->end;

if ($level == 'koordinator') {
        if ($gd == '13') {
               if ($start != null){
                $trjg = TukarjagaModel::with('site')
                    ->whereBetween('tanggal', [$start, $end])
                    ->where('lokasi','=', $gd)
                    ->orwhere(function ($query) use ($start , $end){
                        $query->whereIn('lokasi', ['1','3','14','15'])
                            ->whereBetween('tanggal', [$start, $end]);
                    })
                    ->orderBy('created_at', 'DESC')
                    ->paginate(100000);
                } 
            } else {
                if ($start != null){
                $trjg = TukarjagaModel::with('site')
                    ->whereBetween('tanggal', [$start, $end])
                    ->where('lokasi','=',$gd)
                    ->orderBy('created_at', 'DESC')
                    ->paginate(100000);
                } 
            } 
          } else {
                $trjg = TukarjagaModel::with('site')
                    ->whereBetween('tanggal', [$start, $end])
                    ->orderBy('created_at', 'DESC')
                    ->paginate(100000);
                    $trjg->appends(['start' => $start, 'end' => $end]);
        }
                
    return view('tukarjaga.saveexcel', ['trjg' => $trjg]);
    }
}

        