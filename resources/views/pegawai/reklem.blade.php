@php
    use Carbon\Carbon;

    $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1);
    $jumlahHari = $tanggalAwal->daysInMonth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Laporan Lembur Pegawai</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

<style>
  @page {
    size: A4 landscape;
    margin: 0.5cm 0.5cm 0.5cm 0.5cm;
  }

  body {
    font-size: 8px;
    font-family: Arial, sans-serif;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    table-layout: auto; /* agar kolom bisa autofit isi */
  }

  th, td {
    border: 1px solid #000;
    text-align: center;
    padding: 0.2px;
    word-wrap: break-word;
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

  /* Set kolom tertentu agar lebih sempit */
  td:nth-child(2) { width: 40px; }   /* NIP */
  td:nth-child(3) { width: 120px; }  /* Nama */

  th[colspan="31"] > div {
    display: flex;
    justify-content: space-between;
  }

  h2, h3, h4 {
    margin: 0;
    padding: 0;
  }

  .header {
    text-align: center;
    margin-bottom: 10px;
    padding-top: 10px;
  }

  .header img {
    float: left;
    height: 60px;
    margin-right: 10px;
    margin-top: -20px;
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

  /* Tambahan agar kolom dengan tanda '-' kecil */
  td:empty::after {
    content: "-";
    color: #000;
  }

  td:empty {
    width: 10px;
    max-width: 10px;
    min-width: 10px;
  }
</style>

</head>

<body class="A4 landscape">
  <section class="sheet padding-10mm">
    <div class="header">
      <img src="{{ public_path('storage/img/logo.png') }}" alt="Logo" />
      <div class="text-uppercase" style="font-size: 10pt !important; margin-bottom: 15px; margin-top: -15px;">
        <h4 style="text-align: center;">Rekap Lembur Bulanan</h4>
        @if ($satker == null)
        <strong>Departemen {{$depar->nama_dept}}<br></strong>
          @else
          <strong>Departemen {{$depar->nama_dept}}<br></strong>
        <strong>Satuan Kerja {{$sat->satuan_kerja}}<br></strong>
        @endif
        <strong>PERIODE {{Carbon::parse($periode)->isoFormat('MMMM YYYY')}}</strong><br>
      </div>
    </div>

<table>
  <thead>
    <tr>
      <th rowspan="2">No</th>
      <th rowspan="2">NIP</th>
      <th rowspan="2">Nama</th>
      @if($satker == null)
      <th rowspan="2">Satker</th>
      @endif
      <th colspan="31">Tanggal</th>
      <th rowspan="2">Total Jam</th>
    </tr>
    <tr>
      @for ($i = 1; $i <= 31; $i++)
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
      @if($satker == null)
      <td class="text-start">{{ $r['sat'] }}</td>
      @endif

      @php $totalSeconds = 0; @endphp

@for ($i = 1; $i <= 31; $i++)
    @php
        $tgl = \Carbon\Carbon::createFromDate($tahun, $bulan, $i)->toDateString();
        $lembur = $r['lembur']->firstWhere('tgl_absen', $tgl);
    @endphp
    <td>
      @if ($lembur && $lembur->jam_in && $lembur->jam_out)
        <span>{{ $lembur->jam_in }}</span><br>
        <span>{{ $lembur->jam_out }}</span>

        @php
          try {
              $jamIn = Carbon::createFromFormat('H:i:s', $lembur->jam_in);
              $jamOut = Carbon::createFromFormat('H:i:s', $lembur->jam_out);

              if ($jamOut->lt($jamIn)) {
                  $jamOut->addDay();
              }

              $diff = $jamIn->diffInSeconds($jamOut);
              $totalSeconds += $diff;
          } catch (\Exception $e) {
              // log error jika format tidak sesuai
          }
        @endphp
      @else
        <span>-</span>
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
        // Tampilkan detik jika detik > 0, atau jika semuanya nol
        $waktuLembur[] = $totalDetik . ' d';
    }

    $outputLembur = implode(' ', $waktuLembur);
@endphp
<td>
  <strong>{{ $outputLembur }}</strong>
</td>
    </tr>
    @endforeach
  </tbody>
</table>
  </section>

</html>
