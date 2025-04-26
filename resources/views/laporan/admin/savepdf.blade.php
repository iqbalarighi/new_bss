<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kegiatan Admin</title>
    @php
    \Carbon\Carbon::setLocale('id');
    @endphp
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            padding: 5px;
        }
        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
        }
        .text-justify {
            text-align: justify;
            text-justify: inter-word;
        }
        .dokumentasi {
            text-align: center;
            margin-top: 20px;
        }
        img {
            max-width: 250px; /* Atur sesuai kebutuhan */
            height: auto;
            margin: 10px;
        }
        pre {
            font-family: system-ui, sans-serif;
        }

    </style>
</head>
<body>

    <div class="judul">Laporan Kegiatan Admin</div>
    <div class="judul">Gedung {{ $detail->kant->nama_kantor ?? '' }}</div>
    <div class="judul">{{ \Carbon\Carbon::parse($detail->created_at)->isoFormat('dddd, D MMMM Y') }}</div>
    <div class="judul">Pukul {{ \Carbon\Carbon::parse($detail->updated_at)->isoFormat('HH:mm:ss') }} WIB</div>

    <table>
        <tr>
            <td><b>No. Laporan:</b> {{ $detail->no_lap }}</td>
        </tr>
        <tr>
            <td><b>Personil Yang Bertugas:</b></td>
        </tr>
        <tr>
            <td class=""><pre>{{ ($detail->personil) }}</pre></td>
        </tr>
        <tr>
            <td><b>Update Giat:</b></td>
        </tr>
        <tr>
            <td class="text-justify">{{ nl2br(e($detail->kegiatan)) }}</td>
        </tr>
        <tr>
            <td><b>Keterangan:</b></td>
        </tr>
        <tr>
            <td class="text-justify">{{ nl2br(e($detail->keterangan)) }}</td>
        </tr>
        <tr>
            <td class="dokumentasi">
                <b>Dokumentasi:</b><br><br>
                @if ($detail->foto != null)
                    @foreach (explode('|', $detail->foto) as $item)
                        <img class="dokumentasi" src="{{ public_path('storage/laporan/admin/' . $detail->no_lap . '/' . $item) }}" alt="Foto Dokumentasi">
                    @endforeach
                @else
                    Harap Upload Foto Dokumentasi
                @endif
            </td>
        </tr>
    </table>

</body>
</html>
