<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Laporan Lemburan Karyawan</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
  <style>
    @page {
      size: A4
    }

    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
    }

    h2, h3 {
      margin: 0;
      padding: 0;
    }

    .header {
      text-align: center;
      margin-bottom: 10px;
    }

    .header img {
      float: left;
      height: 60px;
      margin-right: 10px;
    }

    .info-section {
      margin-top: 20px;
      display: flex;
    }

    .info-photo {
      width: 120px;
      text-align: center;
    }

    .info-photo img {
      width: 100px;
      height: 120px;
      object-fit: cover;
      border: 1px solid #ccc;
    }

    .info-details {
      padding-left: 20px;
    }

    .info-details table td {
      padding: 2px 5px;
    }

    table.data {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table.data, table.data th, table.data td {
      border: 1px solid black;
    }

    table.data th, table.data td {
      text-align: center;
      padding: 2px;
    }

    .signature {
      margin-top: 30px;
      width: 100%;
      text-align: center;
    }

    .signature td {
      padding-top: 50px;
    }

    .location-date {
      text-align: right;
      margin-top: 20px;
    }

    .text-lowercase {
    text-transform: lowercase;
    }
    .text-uppercase {
        text-transform: uppercase;
    }
    .text-capitalize {
        text-transform: capitalize;
    }

      .red {
    color: red;
  }
  </style>
</head>

<body class="A4">
  <section class="sheet padding-10mm">
    <div class="header">
      @if(file_exists(public_path('storage/img/logo.png')))
          <img src="{{ public_path('storage/img/logo.png') }}">
      @endif
      <div>
        <h3>LAPORAN LEMBURAN KARYAWAN</h3>
        <strong>PERIODE <font class="text-uppercase">{{Carbon\Carbon::parse($periode)->isoFormat('MMMM YYYY')}}</font></strong><br>
        <strong>{{$pegawai->perusa->perusahaan}}</strong><br>
        <small>{{$pegawai->perusa->alamat}}</small>
      </div>
    </div>

<table width="100%" style="margin-bottom: 20px;">
  <tr>
    <td width="60" valign="top">
      @if($pegawai->foto && file_exists(public_path('storage/foto_pegawai/'.$pegawai->nip.'/'.$pegawai->foto)))
        <img src="{{ public_path('storage/foto_pegawai/'.$pegawai->nip.'/'.$pegawai->foto) }}" alt="Foto Pegawai" width="65">
      @else
        <div style="width: 75px; height: 90px; background: #ccc;"></div>
      @endif
    </td>
    <td valign="top" align="left">
      <table>
        <tr><td><strong>NIK</strong></td><td>: {{$pegawai->nip}}</td></tr>
        <tr><td><strong>Nama Karyawan</strong></td><td class="text-uppercase">: {{$pegawai->nama_lengkap}}</td></tr>
        <tr><td><strong>Jabatan</strong></td><td>: {{$pegawai->jabat->jabatan}}</td></tr>
        <tr><td><strong>Departemen</strong></td><td>: {{$pegawai->deptmn->nama_dept}}</td></tr>
        <tr><td><strong>No. HP</strong></td><td>: {{$pegawai->no_hp}}</td></tr>
      </table>
    </td>
  </tr>
</table>

<table class="data">
    <thead>
      <tr>
        <th>No.</th>
        <th>Tanggal</th>
        <th>Jam Mulai</th>
        <th>Foto</th>
        <th>Jam Selesai</th>
        <th>Foto</th>
        <th>Area Kerja</th>
        <th>Keterangan</th>
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
          {{ $a->jam_in ?? '-' }}
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
        <td>{{ $a->jam_out ?? '-' }}</td>

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
              $jamMasuk = \Carbon\Carbon::parse($a->tgl_absen . ' ' . $a->jam_in);
              $jamPulang = \Carbon\Carbon::parse($a->tgl_absen . ' ' . $a->jam_out);

              if ($jamPulang->lt($jamMasuk)) {
                  $jamPulang->addDay(); // shift malam
              }

              $durasi = $jamPulang->diff($jamMasuk);
              echo ($durasi->h ? $durasi->h . ' j ' : '') .
                   ($durasi->i ? $durasi->i . ' m ' : '') .
                   ($durasi->s ? $durasi->s . ' d' : '');
            } else {
              echo "Berlangsung";
            }
          @endphp
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

{{--     <div class="location-date">Jakarta, {{Carbon\Carbon::now()->locale('id')->isoFormat('DD-MM-YYYY')}}</div>

    <table class="signature">
      <tr>
        <td>Qiana Aqila<br><strong>HRD Manager</strong></td>
        <td>Daffa<br><strong>Direktur</strong></td>
      </tr>
    </table> --}}
  </section>
</body>

</html>
