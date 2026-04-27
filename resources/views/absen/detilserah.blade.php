@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="{{ route('tukar-jaga.index') }}" class="headerButton ">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Detail Serah Terima Jaga</div>
    <div class="right">
        <a href="{{route('tukar-jaga.edit', $detail->id)}}" class="btn btn-sm btn-warning">
            Edit
        </a>
    </div>
</div>
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (Session::get('success'))
<script>
Swal.fire({
    icon: "success",
    title: "{{ Session::get('success') }}",
    showConfirmButton: true,
});
</script>
@endif

@if (Session::get('error'))
<script>
Swal.fire({
    icon: "warning",
    title: "{{ Session::get('error') }}",
    showConfirmButton: true,
});
</script>
@endif

@php
\Carbon\Carbon::setLocale('id');
@endphp

<div class="container" style="margin-top:4rem; margin-bottom:5rem;">
    <div class="card">
        <div class="card-body d-flex justify-content-center" style="overflow:auto; ">
    <div class="col-md-auto p-auto">

        <table class="" border="0">

            <tr>
                <td>
                    <b><center>Laporan Serah Terima Jaga</center></b>
                    <b><center>{{ $detail->lokasi_gedung }}</center></b>
                    <b><center>{{ \Carbon\Carbon::parse($detail->created_at)->isoFormat('dddd, D MMMM Y') }}</center></b>
                    <b><center>Pukul {{ \Carbon\Carbon::parse($detail->created_at)->isoFormat('HH:mm:ss') }} WIB</center></b>
                </td>
            </tr>

            <tr>
                <td><b>No. Laporan:</b> {{ $detail->no_lap }}</td>
            </tr>

            <tr>
                <td><b>Shift:</b> {{ $detail->shift }}</td>
            </tr>

            <tr>
                <td><b>Shift Lama:</b></td>
            </tr>
            <tr>
                <td>
                    <pre class="mb-0">{{ $detail->petugas_lama }}</pre>
                </td>
            </tr>

            <tr>
                <td><b>Shift Baru:</b></td>
            </tr>
            <tr>
                <td>
                    <pre class="mb-0">{{ $detail->petugas_baru }}</pre>
                </td>
            </tr>

            <tr>
                <td><b>Kejadian / Kegiatan:</b></td>
            </tr>
            <tr>
                <td>
                    <div style="white-space:pre-wrap; word-wrap:break-word;">{{ $detail->kejadian ?? '-' }}
                    </div>
                </td>
            </tr>

            <tr>
                <td><b>Serah Terima Barang:</b></td>
            </tr>

            <tr>
                <td>
                    @if($detail->barang->count() > 0)

                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th width="70">Jumlah</th>
                                    <th>Kondisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detail->barang as $b)
                                <tr>
                                    <td>{{ $b->nama_barang }}</td>
                                    <td>{{ $b->jumlah }}</td>
                                    <td>{{ $b->kondisi }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @else
                        <i>Tidak ada barang diserahterimakan</i>
                    @endif
                </td>
            </tr>

        </table>

        <center>
            <a href="{{ route('tukar-jaga.pdf', $detail->id) }}" class="btn btn-primary btn-sm" target="_blank">
                Download PDF
            </a>

        </center>

    </div>
</div>
</div>
</div>

@endsection
