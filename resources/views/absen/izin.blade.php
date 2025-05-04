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


<div class="fab-button bottom-right" style="margin-bottom: 70px;">
    <a href="{{route('absen.formizin')}}" class="fab"><ion-icon name="add-outline"></ion-icon></a>
</div>

<div class="row" style="margin-top: 4rem;">
    <div class="col">
        @foreach ($izin as $d)
        <ul class="listview image-listview">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            <b>{{ date("d-m-Y", strtotime($d->tanggal)) }} ({{ $d->jenis_izin == "s" ? "Sakit" : "Izin" }})</b><br>
                            <small class="text-muted">{{ $d->keterangan }}</small>
                        </div>
                        @if ($d->status_approve == 0)
                        <span class="badge bg-warning">Waiting</span>
                        @elseif($d->status_approve == 1)
                        <span class="badge bg-success">Approved</span>
                        @elseif($d->status_approve == 2)
                        <span class="badge bg-danger">Decline</span>
                        @endif
                    </div>
                </div>
            </li>
        </ul>
        @endforeach
    </div>
</div>
@endsection