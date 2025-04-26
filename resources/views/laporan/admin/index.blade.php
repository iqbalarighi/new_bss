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
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>No. Laporan</th>
                                <th>Nama</th>
                                <th>Kantor</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($lapor as $num => $lap)
                            <tr class="text-center">
                                <td onclick="window.location='/laporan/admin/detail/{{$lap->id}}'" style="cursor:pointer;">{{$lapor->firstItem() + $num}}</td>
                                <td onclick="window.location='/laporan/admin/detail/{{$lap->id}}'" style="cursor:pointer;">{{$lap->no_lap}}</td>
                                <td onclick="window.location='/laporan/admin/detail/{{$lap->id}}'" style="cursor:pointer;">{{$lap->usr->name}}</td>
                                <td onclick="window.location='/laporan/admin/detail/{{$lap->id}}'" style="cursor:pointer;">{{$lap->usr->kant->nama_kantor ?? ''}}</td>
                                <td onclick="window.location='/laporan/admin/detail/{{$lap->id}}'" style="cursor:pointer;">{{Carbon\Carbon::parse($lap->created_at)->format('d-m-Y')}}</td>
                                <td onclick="window.location='/laporan/admin/detail/{{$lap->id}}'" style="cursor:pointer;">{{Carbon\Carbon::parse($lap->created_at)->format('H:i')}}</td>
                                <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection