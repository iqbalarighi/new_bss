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
        </tr>
        <tr>
            <td colspan="9" style="text-align:center;"><b>LAPORAN LEMBUR KARYAWAN</b></td>
        </tr>
        <tr><td colspan="9" style="text-align:center;">PERIODE {{ \Carbon\Carbon::parse($periode)->isoFormat('MMMM YYYY') }}</td></tr>
        <tr><td colspan="9" style="text-align:center;">{{ $pegawai->perusa->perusahaan ?? '-' }}</td></tr>
        <tr><td colspan="9" style="text-align:center;">{{ $pegawai->perusa->alamat ?? '-' }}</td></tr>
    </table>

    <table>
    <tr>
        <td colspan="4" class="info">NIK : {{ $pegawai->nip }}</td>
    </tr>
    <tr>
        <td colspan="4" class="info">Nama Karyawan : {{ strtoupper($pegawai->nama_lengkap) }}</td>
    </tr>
    <tr>
        <td colspan="4" class="info">Jabatan : {{ $pegawai->jabat->jabatan ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="4" class="info">Departemen : {{ $pegawai->deptmn->nama_dept ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="4" class="info">No. HP : {{ $pegawai->no_hp }}</td>
    </tr>
</table>
<table border="1" cellpadding="3">
        <thead>
      <tr>
        <th>No.</th>
        <th>Tanggal</th>
        <th>Jam Mulai</th>
        <th>Foto</th>
        <th>Jam Selesai</th>
        <th>Foto</th>
        <th>Area Kerja</th>
        <th>Keperluan Lembur</th>
        <th>Jumlah Jam Lembur</th>
      </tr>
    </thead>
    <tbody>
      @foreach($lembur as $a)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ \Carbon\Carbon::parse($a->tgl_absen)->locale('id')->translatedFormat('d F Y') }}</td>

        {{-- Jam Masuk --}}
        <td>
          {{ Carbon\Carbon::parse($a->jam_in)->format('H:i:s') ?? '-' }}
        </td>

        {{-- Foto Masuk --}}
        <td>
          @if($a->foto_in && file_exists(public_path('storage/lembur/'.$a->pegawai->nip.'/'.$a->foto_in)))
            <img src="{{ public_path('storage/lembur/'.$a->pegawai->nip.'/'.$a->foto_in) }}" width="30">
          @else
            -
          @endif
        </td>

        {{-- Jam Pulang --}}
        <td>{{ Carbon\Carbon::parse($a->jam_out)->format('H:i:s') ?? '-' }}</td>

        {{-- Foto Pulang --}}
        <td>
          @if($a->foto_out && file_exists(public_path('storage/lembur/'.$a->pegawai->nip.'/'.$a->foto_out)))
            <img src="{{ public_path('storage/lembur/'.$a->pegawai->nip.'/'.$a->foto_out) }}" width="30">
          @else
            -
          @endif
        </td>
        <td>
          {{$a->area_kerja}}
        </td>
        <td>
          {{$a->uraian}}
        </td>

        {{-- Durasi Kerja --}}
        <td>
          @php
            if ($a->jam_in && $a->jam_out) {
              $jamMasuk = \Carbon\Carbon::parse($a->jam_in);
              $jamPulang = \Carbon\Carbon::parse($a->jam_out);

              if ($jamPulang->lt($jamMasuk)) {
                  $jamPulang->addDay(); // shift malam
              }

              $durasi = $jamPulang->diff($jamMasuk);
              echo ($durasi->h ? $durasi->h . ' jam ' : '') .
                   ($durasi->i ? $durasi->i . ' menit ' : '') .
                   ($durasi->s ? $durasi->s . ' detik' : '');
            } else {
              echo "Berlangsung";
            }
          @endphp
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>