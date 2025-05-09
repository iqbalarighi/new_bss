@php
    use Carbon\Carbon;

    $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1);
    $jumlahHari = $tanggalAwal->daysInMonth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Laporan Presensi Karyawan</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <style>
   @page { size: A4 landscape }

  body {
    font-size: 8px;
    font-family: Arial, sans-serif;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
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
      margin-bottom: 10px;
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

<body class="A4 landscape">
  <section class="sheet padding-10mm">
    <div class="header">
      <img src="{{ asset('storage/img/logo.png') }}" alt="Logo" />
      <div class="text-uppercase" style="font-size: 10pt !important;">
        <h4 style="text-align: center;">Rekap Absensi Bulanan</h4>
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
          <th rowspan="2">NIK</th>
          <th rowspan="2">Nama</th>
          @if($satker == null)
          <th rowspan="2">Satker</th>
          @endif
          <th colspan="31">Tanggal</th>
          <th rowspan="2">THE</th>
        </tr>
        <tr>
          <!-- Kolom Tanggal 1-31 -->
          <!-- Bisa disesuaikan agar dinamis -->
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
          <td class="text-start" style="white-space: wrap;">{{ $r['nama'] }}</td>
          @if($satker == null)
          <td class="text-start" style="white-space: wrap;">{{ $r['sat'] }}</td>
          @endif
          @for ($i = 1; $i <= 31; $i++)
          @php
            $tgl = \Carbon\Carbon::createFromDate($tahun, $bulan, $i)->toDateString();
            $absen = $r['absensi']->firstWhere('tgl_absen', $tgl);
            $izin = $r['izin']->firstWhere('tanggal', $tgl);
          @endphp
          <td>
            @if ($absen)
              <span class="{{$absen->jam_in > $absen->shifts->jam_masuk ? 'red' : ''}}">{{ $absen->jam_in ?? '' }}</span><br>
              <span class="{{$absen->jam_out ?? 'red'}}">{{ $absen->jam_out ?? '00:00:00' }}</span>
            @elseif ($izin)
                    @if (Str::contains(strtolower($izin->jenis_izin), 's'))
                        <span style="color:red; font-weight:bold;">S</span>
                    @elseif (Str::contains(strtolower($izin->jenis_izin), 'i'))
                        <span style="color:green; font-weight:bold;">I</span>
                    @else
                        <span style="color:blue; font-weight:bold;">C</span>
                    @endif
                @else
                    {{-- Tidak ada data izin --}}
                    <span>-</span>
            @endif
          </td>
        @endfor

          @php
              $totalHadir = $r['absensi']->count();
              $totalIzin = $r['izin']->count(); // hanya izin yang sudah "diterima" karena sudah difilter di controller
              $totalTHE = $totalHadir + $totalIzin;
            @endphp
            <td><strong>{{ $totalTHE }}</strong></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </section>

</html>
