@extends('layouts.side.side')

{{-- @section('content')
<div class="container" style="height: 100%;">
    <div class="row justify-content-center" style="height: 100%;">
        <div class="col mw-100">
            <div class="card" style="height: 100%;">
                <div class="card-header">{{ __('Dashboard') }}</div>
                    <div class="row px-1">
                        <div class="col-md-3 mb-2">
                            <div class="card shadow-sm p-2">
                                <div class="card-body d-flex align-items-center" style="background-color: #e6f4ea; border-radius: 8px;">
                                    <i class="bi bi-fingerprint text-success" style="font-size: 2rem; margin-right: 10px;"></i>
                                    <div>
                                        <h6 class="mb-0">1</h6>
                                        <small>Karyawan Hadir</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-2">
                            <div class="card shadow-sm p-2">
                                <div class="card-body d-flex align-items-center" style="background-color: #e6f0ff; border-radius: 8px;">
                                    <i class="bi bi-file-earmark-text text-primary" style="font-size: 2rem; margin-right: 10px;"></i>
                                    <div>
                                        <h6 class="mb-0">1</h6>
                                        <small>Karyawan Izin</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-2">
                            <div class="card shadow-sm p-2">
                                <div class="card-body d-flex align-items-center" style="background-color: #fff4e6; border-radius: 8px;">
                                    <i class="bi bi-emoji-frown text-warning" style="font-size: 2rem; margin-right: 10px;"></i>
                                    <div>
                                        <h6 class="mb-0">1</h6>
                                        <small>Karyawan Sakit</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-2">
                            <div class="card shadow-sm p-2">
                                <div class="card-body d-flex align-items-center" style="background-color: #ffe6e6; border-radius: 8px;">
                                    <i class="bi bi-alarm text-danger" style="font-size: 2rem; margin-right: 10px;"></i>
                                    <div>
                                        <h6 class="mb-0">1</h6>
                                        <small>Karyawan Terlambat</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

@section('content')
<div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <!-- Page pre-title -->
        <div class="page-pretitle">Dashboard</div>
        <h2 class="page-title">Data Pegawai</h2>
      </div>
    </div>
    <div class="row justify-content-center">
        <div class="row px-1">
            <div class="col-md-3 mb-2">
                <div class="card shadow-sm p-2">
                    <div class="card-body d-flex align-items-center" style="background-color: #e6f4ea; border-radius: 8px;">
                        <i class="bi bi-fingerprint text-success" style="font-size: 2rem; margin-right: 10px;"></i>
                        <div>
                            <h6 class="mb-0">{{$rekap->jmlhadir}}</h6>
                            <small>Karyawan Hadir</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-2">
                <div class="card shadow-sm p-2">
                    <div class="card-body d-flex align-items-center" style="background-color: #e6f0ff; border-radius: 8px;">
                        <i class="bi bi-file-earmark-text text-primary" style="font-size: 2rem; margin-right: 10px;"></i>
                        <div>
                            <h6 class="mb-0">{{$rekapizin->izin}}</h6>
                            <small>Karyawan Izin</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-2">
                <div class="card shadow-sm p-2">
                    <div class="card-body d-flex align-items-center" style="background-color: #fff4e6; border-radius: 8px;">
                        <i class="bi bi-emoji-frown text-warning" style="font-size: 2rem; margin-right: 10px;"></i>
                        <div>
                            <h6 class="mb-0">{{$rekapizin->sakit}}</h6>
                            <small>Karyawan Sakit</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-2">
                <div class="card shadow-sm p-2">
                    <div class="card-body d-flex align-items-center" style="background-color: #ffe6e6; border-radius: 8px;">
                        <i class="bi bi-alarm text-danger" style="font-size: 2rem; margin-right: 10px;"></i>
                        <div>
                            <h6 class="mb-0">{{$rekap->jmltelat}}</h6>
                            <small>Karyawan Terlambat</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection