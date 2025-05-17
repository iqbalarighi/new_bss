<!DOCTYPE html>
<html>
<head>
    <style>
        td, th { vertical-align: middle; text-align: center; }
        .info { text-align: left; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td height="15px;"><img src="{{ public_path('storage/img/logo.png') }}" height="80" /></td>
            <td colspan="7" style="text-align:center;"><b>LAPORAN PRESENSI KARYAWAN</b></td>
        </tr>
        <tr><td colspan="8" style="text-align:center;">PERIODE {{ \Carbon\Carbon::parse($periode)->isoFormat('MMMM YYYY') }}</td></tr>
        <tr><td colspan="8" style="text-align:center;">{{ $pegawai->perusa->perusahaan ?? '-' }}</td></tr>
        <tr><td colspan="8" style="text-align:center;">{{ $pegawai->perusa->alamat ?? '-' }}</td></tr>
    </table>

    <table>
        <tr>
            <td class="info">NIK : {{ $pegawai->nip }}</td>
        </tr>
        <tr>
            <td class="info">Nama Karyawan : {{ strtoupper($pegawai->nama_lengkap) }}</td>
        </tr>
        <tr>
            <td class="info">Jabatan : {{ $pegawai->jabat->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info">Departemen : {{ $pegawai->deptmn->nama_dept ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info">No. HP : {{ $pegawai->no_hp }}</td>
        </tr>
    </table>

    <table border="1" cellpadding="3">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Foto Masuk</th>
                <th>Jam Pulang</th>
                <th>Foto Pulang</th>
                <th>Keterangan</th>
                <th>Jumlah Jam Kerja</th>
            </tr>
        </thead>
        <tbody>
        @foreach($absen as $i => $a)
            @php
                $jamMasuk = \Carbon\Carbon::parse($a->jam_in);
                $jamStandar = \Carbon\Carbon::parse($a->shifts->jam_masuk);
                $terlambat = $jamMasuk->gt($jamStandar)
                    ? 'Terlambat ' . $jamMasuk->diff($jamStandar)->format('%h jam %i menit %s detik')
                    : 'Tepat waktu';

                $jamKerja = 'Belum absen pulang';
                if ($a->jam_out) {
                    $jamIn = \Carbon\Carbon::parse($a->tgl_absen . ' ' . $a->jam_in);
                    $jamOut = \Carbon\Carbon::parse($a->tgl_absen . ' ' . $a->jam_out);
                    if ($jamOut->lt($jamIn)) $jamOut->addDay();
                    $durasi = $jamOut->diff($jamIn);
                    $jamKerja = $durasi->format('%h jam %i menit %s detik');
                }
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $a->tgl_absen }}</td>
                <td style="color:{{ $jamMasuk->gt($jamStandar) ? 'red' : 'black' }}">{{ $a->jam_in }}</td>
                <td align="center">
                    @if($a->foto_in)
                        <img src="{{ public_path('storage/absensi/' . $a->pegawai->nip . '/' . $a->foto_in) }}" width="50">
                    @endif
                </td>
                <td>{{ $a->jam_out ?? '-'}}</td>
                <td align="center">
                    @if($a->foto_out != null)
                        <img src="{{ public_path('storage/absensi/' . $a->pegawai->nip . '/' . $a->foto_out) }}" width="50">
                    @else
                    -
                    @endif
                </td>
                <td>{{ $terlambat }}</td>
                <td>{{ $jamKerja }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
