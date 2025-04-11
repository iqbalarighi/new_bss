<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Laporan Presensi Karyawan</title>

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
  </style>
</head>

<body class="A4">
  <section class="sheet padding-10mm">
    <div class="header">
      <img src="{{ asset('storage/img/logo.png') }}" alt="Logo" />
      <div>
        <h3>LAPORAN PRESENSI KARYAWAN</h3>
        <strong>PERIODE <font class="text-uppercase">{{Carbon\Carbon::parse($periode)->isoFormat('MMMM YYYY')}}</font></strong><br>
        <strong>{{$pegawai->perusa->perusahaan}}</strong><br>
        <small>{{$pegawai->perusa->alamat}}</small>
      </div>
    </div>

    <div class="info-section">
      <div class="info-photo">
        <img src="{{ asset('storage/foto_pegawai/'.$pegawai->nip.'/'.$pegawai->foto) }}" alt="Foto Pegawai" />
      </div>
      <div class="info-details">
        <table>
          <tr><td>NIK</td><td>: {{$pegawai->nip}}</td></tr>
          <tr><td>Nama Karyawan</td><td class="text-uppercase">: {{$pegawai->nama_lengkap}}</td></tr>
          <tr><td>Jabatan</td><td>: {{$pegawai->jabat->jabatan}}</td></tr>
          <tr><td>Departemen</td><td>: {{$pegawai->deptmn->nama_dept}}</td></tr>
          <tr><td>No. HP</td><td>: {{$pegawai->no_hp}}</td></tr>
        </table>
      </div>
    </div>

    <table class="data">
      <thead>
        <tr>
          <th>No.</th>
          <th>Tanggal</th>
          <th>Jam Masuk</th>
          <th>Foto</th>
          <th>Jam Pulang</th>
          <th>Foto</th>
          <th>Keterangan</th>
          <th>Jumlah Jam Kerja</th>
        </tr>
      </thead>
      <tbody>
@foreach($absen as $a)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{$a->tgl_absen}}</td>
          <td>{{$a->jam_in}}</td>
          <td><img src="{{ asset('storage/absensi/'. $a->pegawai->nip.'/'.$a->foto_in) }}" width="30"></td>
          <td>{{$a->jam_out}}</td>
          <td><img src="{{ asset('storage/absensi/'. $a->pegawai->nip.'/'.$a->foto_out) }}" width="30"></td>
          <td>
            @php
              $jamStandar = Carbon\Carbon::parse($a->shifts->jam_masuk);

          $jamAktual = Carbon\Carbon::parse($a->jam_in); // misalnya: '08:23'

          if ($jamAktual->gt($jamStandar)) {
              $selisih = $jamAktual->diff($jamStandar);
          echo "Terlambat <br/>" . ($selisih->h == 0 ? '' : $selisih->h . ' jam ').
                              ($selisih->i == 0 ? '' : $selisih->i . ' menit ').
                              ($selisih->s == 0 ? '' : $selisih->s . ' detik ');
          } else {
              echo "Tepat waktu";
          }
          @endphp
          </td>
          <td>
            @php
                // Gabungkan tanggal dan jam_in
              $tglAbsen = Carbon\Carbon::parse($a->tgl_absen); // misalnya: 2025-04-10
              $jamMasuk = Carbon\Carbon::parse($a->tgl_absen . ' ' . $a->jam_in);
              
              $jamPulang = null;
              if ($a->jam_out) {
                  // Default, jam_out di hari yang sama
                  $jamPulang = Carbon\Carbon::parse($a->tgl_absen . ' ' . $a->jam_out);

                  // Jika jam_out lebih kecil dari jam_in → berarti lewat tengah malam (shift malam)
                  if ($jamPulang->lt($jamMasuk)) {
                      $jamPulang->addDay(); // Tambah 1 hari
                  }

                  $durasiKerja = $jamPulang->diff($jamMasuk);

                  echo ($durasiKerja->h == 0 ? '' : $durasiKerja->h . ' jam ') .
                      ($durasiKerja->i == 0 ? '' : $durasiKerja->i . ' menit ').
                      ($durasiKerja->s == 0 ? '' : $durasiKerja->s . ' detik');
              } else {
                  echo "Belum absen pulang";
              }
            @endphp
          </td>
        </tr>
    @endforeach
      </tbody>
    </table>

    <div class="location-date">Jakarta, {{Carbon\Carbon::now()->locale('id')->isoFormat('DD-MM-YYYY')}}</div>

    <table class="signature">
      <tr>
        <td>Qiana Aqila<br><strong>HRD Manager</strong></td>
        <td>Daffa<br><strong>Direktur</strong></td>
      </tr>
    </table>
  </section>
</body>

</html>
