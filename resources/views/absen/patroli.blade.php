@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Patroli</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (Session::get('success'))
<script type="text/javascript">
    Swal.fire({
  icon: "success",
  title: "{{Session::get('success')}}",
  showConfirmButton: true,
});
</script>
@endif
@if ($belumAbsen)
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Belum Absen!',
        text: 'Anda belum melakukan absensi hari ini.',
        allowOutsideClick : false,
        confirmButtonText: 'Absen Sekarang'
    }).then((result) => {
        if (result.isConfirmed) {
            // Ganti '/absen' dengan route tujuanmu
            window.location.href = "{{ route('absen.create') }}";
        }
    });
</script>
@endif

<div class="fab-button bottom-right" style="margin-bottom: 70px;">
    <a href="{{route('absen.patrolicheck')}}" class="fab"><ion-icon name="add-outline"></ion-icon></a>
</div>

<div class="row" style="margin-top: 4rem;">
    <div class="col">
        {{-- buat nampilin data patroli disini --}}
@foreach ($show as $d)
    <ul class="listview image-listview px-1 bg-light">
        <li>
            <div class="item d-flex rounded shadow-sm p-2">
                <div class="me-3">
                    <img src="{{ asset('storage/foto_patrol/' . $d->foto) }}" width="100" class="rounded shadow">
                </div>

                <div>
                    <b>{{ $d->karyawan->nama_lengkap }}</b><br>
                    {{ $d->shift }}<br>
                    {{ \Carbon\Carbon::parse($d->waktu_scan)->isoFormat('dddd, D MMMM Y') }}<br>
                    {{ \Carbon\Carbon::parse($d->waktu_scan)->format('H:i:s') }} WIB<br>
                    <b>{{ $d->checkpoint->nama }}</b><br>
                    {{ $d->checkpoint->deskripsi }}<br>
                    {{ $d->keterangan }}
                </div>
            </div>
        </li>
    </ul>
@endforeach

    </div>
</div>

@endsection