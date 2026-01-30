<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 <head>
        <meta charset="utf-8">
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>{{ config('app.name', 'Serah Terima Jaga') }}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

                    <style>
                        .table{
                            border: 1px solid #000;
                        }
                        .xx {
                            font-size: 10pt;
                            text-align: center;
                        }
                        .table tr td {
                            padding:0.2rem;
                            vertical-align: middle;
                            white-space:nowrap;
                            border: 1px solid #000;
                        }
                        .table th {
                            padding:0.3rem;
                            white-space:nowrap;
                            vertical-align: middle;
                            background-color: lightgrey;
                            border: 1px solid #000;
                        }
                        label {
                            margin: 0em;
                        }

                        pre {
                                white-space: pre-line;     /* Since CSS 2.1 */
                            }
                    </style>
                    <style>
                        a {color:black;}
                       a:hover { color:rgb(0, 138, 0);}
                       label:hover { color:rgb(0, 138, 0);}
                    </style>
    </head>
    <body>

<body>

<div class="card-body overflow" style="overflow-x: auto;">

    <div align="center" class="text-center text-uppercase">
        <b>

            {{-- LOGO --}}
<img src="{{public_path('storage/img/logo.png')}}" style="margin-top: 1px; width: 75px; position: fixed;">

            <br>

            LAPORAN SERAH TERIMA JAGA <br>

            {{ $kantor->nama_kantor ?? $header->lokasi_gedung }} <br>

        </b>
    </div>

    <br>

        Hari / Tanggal :
        {{ \Carbon\Carbon::parse($header->tanggal)->isoFormat('dddd, D MMMM Y') }} <br>

        Shift :
        {{ $header->shift }}<br>
        
        Pukul : 
        {{ \Carbon\Carbon::parse($header->created_at)->format('H:i') }} WIB <br>

        Danru :
        {{ $header->karyawan->nama_lengkap ?? '-' }}

    <div>
        <h5>Tukar Shift</h5>
    </div>

    <table border="" class="table" width="300px" style="margin-top: -15px;">
        <tr class="font-weight-normal xx ">
            <th width="50%">Shift Lama</th>
            <th width="50%">Shift Baru</th>
        </tr>

        <tr>
            <td align="left" style="padding-left: 10px;">
                {!! nl2br(e($header->petugas_lama)) !!}
            </td>
            <td align="left" style="padding-left: 10px;">
                {!! nl2br(e($header->petugas_baru)) !!}
            </td>
        </tr>
    </table>

    <br>

    <div>
        <h5>Barang Inventaris Yang Diserahterimakan</h5>
    </div>

    <table border="" class="table " style="margin-top: -15px;">
        <tr class="font-weight-normal xx">
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
        </tr>

        @forelse ($barang as $item)
            <tr>
                <td align="left" style="padding-left: 10px;">{{ $item->nama_barang }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ $item->kondisi }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Tidak ada barang diserahterimakan</td>
            </tr>
        @endforelse
    </table>

    <br>

    <div>
        <h5>Kejadian / Kegiatan</h5>
        <div style="white-space: pre-line; margin-top: -30px;">
            {{ $header->kejadian ?? '-' }}
        </div>
    </div>


</div>

</body>
</html>
