@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="{{ route('absen') }}" class="headerButton ">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Serah Terima Jaga</div>
    <div class="right"></div>
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
@if(Session::get('error'))
<script>
Swal.fire({
    icon: "error",
    title: "Peringatan",
    text: "{{ session('error') }}",
});
</script>
@endif


{{-- tombol tambah --}}

@if($selisihJam > 5 || $selisihJam == null)
<div class="fab-button bottom-right" style="margin-bottom: 70px;">
    <a href="{{ route('tukar-jaga.create') }}" class="fab">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div>
@else
<div class="fab-button bottom-right" onclick="err()" style="margin-bottom: 70px;">
        <a href="#" class="fab">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div>
<script>
    function err() {
        Swal.fire({
        icon: "warning",
        title: "Peringatan",
        text: "Anda baru saja membuat laporan",
    });
    }
</script>
@endif

<div class="row" style="margin-top: 4rem;">
    <div class="col">

        @foreach ($serah as $d)
        <ul class="listview image-listview">
            <li>
                <div class="item" onclick="window.location='{{route('tukar-jaga.show', $d->id) }}'">
                    <div class="in">
                        <div>
                            <b>{{$d->no_lap}}</b><br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($d->created_at)->isoFormat('DD-MM-YYYY HH:mm:ss') }}
                            </small><br>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        @endforeach

    </div>
</div>


@endsection
