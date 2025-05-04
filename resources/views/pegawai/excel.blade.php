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
        <tr><td colspan="{{ $totalKolom }}" style="text-align:center;"><strong>Rekap Absensi Bulanan</strong></td></tr>
        <tr><td colspan="{{ $totalKolom }}" style="text-align:center;"><strong>Departemen: {{ $depar->nama_dept }}</strong></td></tr>
         @if ($satker != null)
        <tr><td colspan="{{ $totalKolom }}" style="text-align:center;"><strong>Satker: {{ $sat->satuan_kerja }}</strong></td></tr>
        @endif
        <tr><td colspan="{{ $totalKolom }}" style="text-align:center;">
            <strong>Periode: {{ Carbon::parse($periode)->isoFormat('MMMM YYYY') }}</strong>
        </td></tr>
    </table>

    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">NIP</th>
                <th rowspan="2">Nama</th>
                @if ($satker == null)
                    <th rowspan="2">Satker</th>
                @endif
                <th colspan="{{ $jumlahHari }}">Tanggal</th>
                <th rowspan="2">THE</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= $jumlahHari; $i++)
                    <th>{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $index => $r)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $r['nip'] }}</td>
                    <td class="text-start">{{ $r['nama'] }}</td>
                    @if ($satker == null)
                        <td class="text-start">{{ $r['sat'] }}</td>
                    @endif
                    @for ($i = 1; $i <= $jumlahHari; $i++)
                        @php
                            $tgl = Carbon::createFromDate($tahun, $bulan, $i)->toDateString();
                            $absen = $r['absensi']->firstWhere('tgl_absen', $tgl);
                            $izin = $r['izin']->firstWhere('tanggal', $tgl);
                        @endphp
                        <td>
                            @if ($absen)
                                {{-- ✓ --}}
                                <span class="{{$absen->jam_in > $absen->shifts->jam_masuk ? 'red' : ''}}">{{ $absen->jam_in ?? '' }}</span><br>
                                <span class="{{$absen->jam_out ?? 'red'}}">{{ $absen->jam_out ?? '00:00:00' }}</span>
                            @elseif ($izin)
                                @if (Str::contains(strtolower($izin->jenis_izin), 's'))
                                    S
                                @elseif (Str::contains(strtolower($izin->jenis_izin), 'i'))
                                    I
                                @else
                                    C
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    @endfor
                    <td>{{ $r['absensi']->count() + $r['izin']->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
