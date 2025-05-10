@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle" id="judulHalaman">Histori</div>
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
                <div class="form-group d-flex justify-content-around">
                    <div class="col-5">
                    	<button class="btn btn-sm btn-primary btn-block btn-hover" onclick="ubahJudul('absen')" id="getabsen">
                    		<ion-icon name="search-outline"></ion-icon> Absen
                    	</button>
                    </div>
                    <div class="col-5">
                    <button class="btn btn-sm btn-info btn-block btn-hover" onclick="ubahJudul('lembur')" id="getlembur">
                        <ion-icon name="search-outline"></ion-icon> Lembur
                    </button>
                    </div>
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
        $("#getabsen").click(function(e) {
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
<script>
    $(function() {
        $("#getlembur").click(function(e) {
            var bultah = $("#bultah").val();
            $.ajax({
                type: 'POST',
                url: '/absen/gethistorilembur',
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
<script>
    function ubahJudul(tipe) {
        const judul = document.getElementById('judulHalaman');
        if (tipe === 'absen') {
            judul.textContent = 'Histori Absen';
        } else if (tipe === 'lembur') {
            judul.textContent = 'Histori Lembur';
        }
    }
</script>
@endpush