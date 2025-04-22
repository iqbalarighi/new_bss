@extends('layouts.side.side')
@section('content')
<div class="container mw-100">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Laporan Admin') }}
                    <a href="{{route('lapor.admin.input')}}" class="btn btn-sm btn-danger">Buat Laporan</a>
                </div>
                <div class="card-body d-flex justify-content-center" style="overflow: auto;">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection