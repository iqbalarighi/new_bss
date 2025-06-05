@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Izin</div>
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
        <ul class="listview image-listview">
            @php
                date_default_timezone_set('Asia/Jakarta'); // Set zona waktu ke WIB
                $now = date('H:i', strtotime($d->waktu_scan));
                $shift = ($now >= '07:00' && $now < '19:00') ? 'Shift Pagi' : 'Shift Malam';
            @endphp

            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            Area : <b>{{$d->checkpoint->nama}}</b> <br>
                            Tanggal : {{ \Carbon\Carbon::parse($d->waktu_scan)->isoFormat('dddd, MMMM Y') }} <br>
                            Jam : {{ \Carbon\Carbon::parse($d->waktu_scan)->Format('H:i:s')}} WIB <br>
                            Personel : {{$d->user->nama_lengkap}} <br>
                            Shift : {{ $d->shift}}  <br>
                            Keterangan : {{ $d->keterangan }}
                        </div>
                        <img src="{{asset('storage/foto_patrol/'.$d->foto)}}" width="100">
                    </div>
                </div>
            </li>
        </ul>
        @endforeach
    </div>
</div>

@endsection