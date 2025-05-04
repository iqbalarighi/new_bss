<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
        <title>{{ config('app.name', 'SISPAM') }}</title>
        <style type="text/css">
            @page {
              size: A4
            }

            body {
              font-family: Arial, sans-serif;
              font-size: 12pt;
            }

            pre {
                font-family : Calibri;
            }
            table {
               page-break-inside: auto;
            }
                
            .potong {
                white-space: pre-line;       /* Internet Explorer 5.5+ */
            }
        </style>
        @php
        \Carbon\Carbon::setLocale('id');
        @endphp
    </head>
<body class="A4">
                <div style="margin-top: -20px;">
                    <img src="{{public_path('storage/img/logo.png')}}" style="margin-top: 1px; width: 75px; position: fixed;">
                    <h4>
                        <b><center>Laporan Kegiatan {{$satker}}</center></b>
                        <b><center>{{$detail->kant->nama_kantor ?? ''}}</center></b>
                        <b><center>{{Carbon\Carbon::parse($detail->tanggal)->isoFormat('dddd, D MMMM Y')}}</center></b>
                        <b><center>Pukul {{Carbon\Carbon::parse($detail->updated_at)->isoFormat('HH:mm:ss')}} WIB</center></b>
                    </h4>
                </div>
                <p></p>
                    <span class="table table-responsive " width="100%">
                    <b>No. laporan: </b>{{$detail->no_lap}}
                    <pre class="mb-0 potong" style=""><b>Personil Yang Bertugas :<br></b>{{$detail->personil}}</pre>
                    
                    <pre class="mb-0 potong" style=""><b>Update Giat :<br></b>{{$detail->kegiatan}}</pre>
                    
                    <pre class="mb-0 potong" style=""><b>Keterangan :<br></b>{{$detail->keterangan}}</pre>
                    </span>
                
                <div style="page-break-after: inherit;" >
                    <b>Dokumentasi : </b>
                     <br><br><br><br><br>
                     <div align="center">
                            @if ($detail->foto != null)
                    @foreach(explode('|',$detail->foto) as $item)

                    <img  src="{{ public_path('storage/laporan')}}/{{$detail->no_lap}}/{{$item}}" style="height:180px;  margin-bottom: 5pt">  &nbsp;
                    @endforeach
                        @else 
                        Harap Upload Foto Dokumentasi
                        @endif
                    </div>
                </div>

</body>
    </html>