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
      padding: 5px;
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

  </style>
</head>

<body class="A4">
  <section class="sheet padding-10mm">
    <div class="header">
      <img src="https://i.imgur.com/6oZ7NSe.png" alt="Logo" />
      <div>
        <h3>LAPORAN PRESENSI KARYAWAN</h3>
        <strong>PERIODE FEBRUARI 2023</strong><br>
        <strong>PT. ADAM ADIFA</strong><br>
        <small>Jln. H. Dahlan No. 75, Kecamatan Sindangrasa, Kabupaten Ciamis</small>
      </div>
    </div>

    <div class="info-section">
      <div class="info-photo">
        <img src="https://i.imgur.com/3W8bG4W.png" alt="Foto Pegawai" />
      </div>
      <div class="info-details">
        <table>
          <tr><td>NIK</td><td>: 12345</td></tr>
          <tr><td>Nama Karyawan</td><td>: Adam Abdi Al Ala S.Kom</td></tr>
          <tr><td>Jabatan</td><td>: Head of IT</td></tr>
          <tr><td>Departemen</td><td>: Keuangan</td></tr>
          <tr><td>No. HP</td><td>: 08967044322</td></tr>
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
          <th>Jml Jam</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>04-02-2023</td>
          <td>16:22:10</td>
          <td>Belum Absen</td>
          <td>Belum Absen</td>
          <td>–</td>
          <td>Terlambat 9:22</td>
          <td>0</td>
        </tr>
        <tr>
          <td>2</td>
          <td>12-02-2023</td>
          <td>17:56:32</td>
          <td>Belum Absen</td>
          <td>Belum Absen</td>
          <td>–</td>
          <td>Terlambat 10:57</td>
          <td>0</td>
        </tr>
        <tr>
          <td>3</td>
          <td>23-02-2023</td>
          <td>23:06:39</td>
          <td><img src="https://i.imgur.com/qzLkoKt.png" width="50"></td>
          <td>23:07:57</td>
          <td><img src="https://i.imgur.com/qzLkoKt.png" width="50"></td>
          <td>Terlambat 16:7</td>
          <td>0.1</td>
        </tr>
      </tbody>
    </table>

    <div class="location-date">Tasikmalaya, 09-03-2023</div>

    <table class="signature">
      <tr>
        <td>Qiana Aqila<br><strong>HRD Manager</strong></td>
        <td>Daffa<br><strong>Direktur</strong></td>
      </tr>
    </table>
  </section>
</body>

</html>
