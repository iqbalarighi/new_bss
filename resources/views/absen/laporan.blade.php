@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Laporan</div>
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

<div class="fab-button bottom-right" style="margin-bottom: 70px;">
    <a href="{{route('absen.formizin')}}" class="fab"><ion-icon name="add-outline"></ion-icon></a>
</div>

<div class="row" style="margin-top: 4rem;">
    <div class="col">
        @foreach ($lapor as $nunm => $d)
        <ul class="listview image-listview">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            <b>{{ $d->no_lap }}</b><br>
                            {{-- <small class="text-muted">{{ $d->keterangan }}</small> --}}
                        </div>

                        <span class="badge bg-success">Approved</span>

                    </div>
                </div>
            </li>
        </ul>
        @endforeach
    </div>
</div>
@endsection