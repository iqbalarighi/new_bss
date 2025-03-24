@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Histori</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="row" style="margin-top:70px">
    <div class="col">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <input type="month" id="bultah" min="2024-01" max="{{Carbon\Carbon::now()->format('Y-m')}}" class="form-control" value="{{Carbon\Carbon::now()->isoFormat('YYYY-MM')}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                	<button class="btn btn-sm btn-primary btn-block btn-hover" id="getdata">
                		<ion-icon name="search-outline"></ion-icon> Cari
                	</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12" id="tampilhistori">
        
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(function() {
        $("#getdata").click(function(e) {
            var bultah = $("#bultah").val();
            $.ajax({
                type: 'POST',
                url: '/absen/gethistori',
                data: {
                    _token: "{{ csrf_token() }}",
                    bultah: bultah
                },
                cache: false,
                success: function(respond) {
                    $("#tampilhistori").html(respond);
                }
            });
        });
    });
</script>
@endpush