@php
    use Carbon\Carbon;
    use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

    $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1);
    $jumlahHari = $tanggalAwal->daysInMonth;
    $totalKolom = ($satker == null ? 4 : 3) + $jumlahHari + 1; // sesuai controller
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-size: 10px;
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
        }
        .text-start {
            text-align: left;
        }
        .red {
            color: red;
          }
    </style>
</head>
<body>
    <table>
        <tr><td colspan="{{ $totalKolom }}" style="text-align:center;"><strong>Rekap Lembur Bulanan</strong></td></tr>
        <tr><td colspan="{{ $totalKolom }}" style="text-align:center;"><strong>Departemen {{ $depar->nama_dept }}</strong></td></tr>
         @if ($satker != null)
        <tr><td colspan="{{ $totalKolom }}" style="text-align:center;"><strong>Satker {{ $sat->satuan_kerja }}</strong></td></tr>
        @endif
        <tr><td colspan="{{ $totalKolom }}" style="text-align:center;">
            <strong>Periode {{ Carbon::parse($periode)->isoFormat('MMMM YYYY') }}</strong>
        </td></tr>
    </table>

<table border="1">
    <thead>
        <tr>
            <th rowspan="2"><strong>No</strong></th>
            <th rowspan="2"><strong>NIP</strong></th>
            <th rowspan="2"><strong>Nama</strong></th>
            @if ($satker == null)
                <th rowspan="2"><strong>Satker</strong></th>
            @endif
            <th colspan="31"><strong>Tanggal</strong></th>
            <th rowspan="2"><strong>Total Jam Lembur</strong></th>
        </tr>
        <tr>
            @for ($i = 1; $i <= 31; $i++)
                <th><strong>{{ $i }}</strong></th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($rekap as $index => $r)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $r['nip'] }}</td>
                <td>{{ $r['nama'] }}</td>
                @if ($satker == null)
                    <td>{{ $r['sat'] }}</td>
                @endif

                @php $totalSeconds = 0; @endphp

                @for ($i = 1; $i <= 31; $i++)
                    @php
                        $tgl = \Carbon\Carbon::createFromDate($tahun, $bulan, $i)->toDateString();
                        $lembur = $r['lembur']->firstWhere('tgl_absen', $tgl);
                    @endphp
                    <td>
                        @if ($lembur && $lembur->jam_in && $lembur->jam_out)
                            {{ Carbon\Carbon::parse($lembur->jam_in)->format('H:i:s') }} - {{ Carbon\Carbon::parse($lembur->jam_out)->format('H:i:s') }}
                            @php
                                try {
                                    $jamIn = \Carbon\Carbon::createFromFormat('H:i:s', $lembur->jam_in);
                                    $jamOut = \Carbon\Carbon::createFromFormat('H:i:s', $lembur->jam_out);

                                    if ($jamOut->lt($jamIn)) {
                                        $jamOut->addDay(); // koreksi lewat tengah malam
                                    }

                                    $diff = $jamIn->diffInSeconds($jamOut);
                                    $totalSeconds += $diff;
                                } catch (\Exception $e) {
                                    // skip jika format salah
                                }
                            @endphp
                        @else
                            -
                        @endif
                    </td>
                @endfor

                @php
                    $totalJam = floor($totalSeconds / 3600);
                    $sisaDetik = $totalSeconds % 3600;
                    $totalMenit = floor($sisaDetik / 60);
                    $totalDetik = $sisaDetik % 60;

                    $waktuLembur = [];

                    if ($totalJam > 0) {
                        $waktuLembur[] = $totalJam . ' j';
                    }
                    if ($totalMenit > 0) {
                        $waktuLembur[] = $totalMenit . ' m';
                    }
                    if ($totalDetik > 0 || empty($waktuLembur)) {
                        $waktuLembur[] = $totalDetik . ' d';
                    }

                    $outputLembur = implode(' ', $waktuLembur);
                @endphp
                <td><strong>{{ $outputLembur }}</strong></td>
            </tr>
        @endforeach
    </tbody>
</table>
