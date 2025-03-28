@extends('layouts.absen.absen')
@section('content')
    <div class="section p-2" id="user-section">
            
            <form id="logout-form" action="{{ route('absen.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <div id="user-detail">
                <div class="avatar">
                    <div class="rounded-circle overflow-hidden shadow-sm bg-secondary text-white d-inline-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
                    @if($pegawai->foto == null)
                    <img src="https://ui-avatars.com/api/?name={{$pegawai->nama_lengkap}}" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <img src={{asset('storage/foto_pegawai/'.Auth::guard('pegawai')->user()->nip.'/'.$pegawai->foto)}} alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                </div>
                </div>
                <div id="user-info" class="col mw-100 px-0">
                    <h2 id="user-name" style="width: 200px;">{{Auth::guard('pegawai')->user()->nama_lengkap}}</h2>
                    <span id="user-role">{{$pegawai->jabat->jabatan}}</span>

                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <button class="btn btn-secondary btn-sm float-right px-1" style="margin-top: -45px;">logout</button>
            </a>
                </div>
            </div>
        </div>

        <div class="section" id="menu-section">
            <div class="card">
                <div class="card-body text-center p-2">
                    <div class="list-menu">
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="#" class="green" style="font-size: 40px;">
                                    <ion-icon name="person-sharp"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Profil</span>
                            </div>
                        </div>
                        {{-- <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="danger" style="font-size: 40px;">
                                    <ion-icon name="calendar-number"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Cuti</span>
                            </div>
                        </div> --}}
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="warning" style="font-size: 40px;">
                                    <ion-icon name="document-text"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Histori</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="orange" style="font-size: 40px;">
                                    <ion-icon name="location"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                Lokasi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="section mt-2" id="presence-section">
            <div class="todaypresence">
                    <h5><ion-icon name="person"></ion-icon> Kehadiran Terakhir</h5>
        @if($absen != null)
        <div class="card p-1 mb-1">
            <h5>{{Carbon\carbon::parse($absen->tgl_absen)->locale('id')->translatedFormat('l, d M Y')}}</h5>
            <div class="d-flex justify-content-around align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('storage/absensi/'.$absen->pegawai->nip.'/'.$absen->foto_in) }}" alt="Foto Masuk" class="rounded" width="50">
                    <div class="text-center pl-1">
                        <span class="d-block">Masuk</span>
                        <strong>{{$absen->jam_in}}</strong>
                    </div>
                </div>
                @if($absen->jam_out == null)
                <div class="d-flex align-items-center gap-2">
                    
                    <div class="text-center pl-1">
                        <span class="d-block">Pulang</span>
                        <strong>--:--</strong>
                    </div>
                </div>
                @else
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('storage/absensi/'.$absen->pegawai->nip.'/'.$absen->foto_out) }}" alt="Foto Masuk" class="rounded" width="50">
                    <div class="text-center pl-1">
                        <span class="d-block">Pulang</span>
                        <strong>{{$absen->jam_out}}</strong>
                    </div>
                </div>
                @endif
            </div>
                @if($absen->jam_out == null)
                <div>
                    <span class="text-warning float-right pr-1">Berlangsung</span>
                </div>
                @endif
        </div>
            @endif
        </div>
           {{-- 
                <div class="row">
                    <div class="col-6">
                        <div class="card gradasigreen">
                            <div class="card-body p-1 m-1">
                                    <div class="presencecontent">
                                        <div class="iconpresence">
                                            @if($absen != null)
                                            <img src="{{ asset('storage/absensi/'.$absen->nip.'/'.$absen->foto_in) }}" class="imaged w64">
                                            @else
                                            <ion-icon name="camera"></ion-icon>
                                            @endif
                                        </div>
                                        <div class="presencedetail">
                                            <h4 class="presencetitle">Masuk</h4>
                                            <span>{{$absen == null ? '' : $absen->jam_in}}</span>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card gradasired">
                            <div class="card-body p-1 m-1">
                                <div class="presencecontent">
                                    <div class="iconpresence">
                                        @if($absen != null && $absen->foto_out != null)
                                            <img src="{{ asset('storage/absensi/'.$absen->nip.'/'.$absen->foto_out) }}" class="imaged w64">
                                            @else
                                            <ion-icon name="camera"></ion-icon>
                                            @endif
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitle">Pulang</h4>
                                        <span>{{$absen == null || $absen->jam_out == null ? '' : $absen->jam_out}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             <div class="rekappresence"> --}}
                {{-- <div id="chartdiv"></div> --}}
                <!-- <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence primary">
                                        <ion-icon name="log-in"></ion-icon>
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="rekappresencetitle">Hadir</h4>
                                        <span class="rekappresencedetail">0 Hari</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence green">
                                        <ion-icon name="document-text"></ion-icon>
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="rekappresencetitle">Izin</h4>
                                        <span class="rekappresencedetail">0 Hari</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence warning">
                                        <ion-icon name="sad"></ion-icon>
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="rekappresencetitle">Sakit</h4>
                                        <span class="rekappresencedetail">0 Hari</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence danger">
                                        <ion-icon name="alarm"></ion-icon>
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="rekappresencetitle">Terlambat</h4>
                                        <span class="rekappresencedetail">0 Hari</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            {{-- </div> --}}
    <style>
        .card-presensi {
            text-align: center;
            padding: 5px;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            background-color: white;
            border: 1px solid #ddd;
            width: 100px;
            height: 70px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .container-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .badge-presensi {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
    </style>           
    <div id="rekappresensi">
                <div class="row">
                    <div class="container px-2">
                        <div class="text-left mt-1">
                            <h5 class="text-dark"><ion-icon name="list"></ion-icon> Rekap Presensi Bulan {{Carbon\Carbon::now()->locale('id')->isoFormat('MMMM');}} Tahun {{Carbon\Carbon::now()->format('Y');}}</h5>
                        </div>
                        <div class="container-card d-flex justify-content-center p-1">
                            <div class="card-presensi">
                                @if ($rekap->jmlhadir != null)
                                <div class="badge-presensi">{{$rekap->jmlhadir}}</div>
                                @endif
                                <ion-icon name="accessibility-outline" size="large" style="color: blue;"></ion-icon>
                                <p class="mb-0 text-dark">Hadir</p>
                            </div>
                            <div class="card-presensi">
                                @if ($rekapizin->izin != null)
                                <div class="badge-presensi">{{$rekapizin->izin}}</div>
                                @endif
                                <ion-icon name="document-text-outline" size="large" style="color: green;"></ion-icon>
                                <p class="mb-0 text-dark">Izin</p>
                            </div>
                            <div class="card-presensi">
                                @if ($rekapizin->sakit != null)
                                <div class="badge-presensi">{{$rekapizin->sakit}}</div>
                                @endif
                                <ion-icon name="medkit-outline" size="large" style="color: orange;"></ion-icon>
                                <p class="mb-0 text-dark">Sakit</p>
                            </div>
                            <div class="card-presensi">
                                @if ($rekap->jmltelat != null)
                                <div class="badge-presensi">{{$rekap->jmltelat}}</div>
                                @endif
                                <ion-icon name="alarm-outline" size="large" style="color: red;"></ion-icon>
                                <p class="mb-0 text-dark">Telat</p>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
            <div class="presencetab mt-2">
                <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                    <ul class="nav nav-tabs style1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                Bulan Ini
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                Leaderboard
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt-2" style="margin-bottom:100px;">
                    <div class="tab-pane fade show active" id="home" role="tabpanel">
                        <ul class="listview image-listview">
                             @foreach($absens as $item)
                            <li>
                               <div class="card p-1 mb-2">
                                    <h5>{{Carbon\carbon::parse($item->tgl_absen)->locale('id')->translatedFormat('l, d M Y')}}</h5>
                                    <div class="d-flex justify-content-around align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset('storage/absensi/'.$item->pegawai->nip.'/'.$item->foto_in) }}" alt="Foto Masuk" class="rounded" width="50">
                                            <div class="text-center pl-1">
                                                <span class="d-block">Masuk</span>
                                                <strong>{{$item->jam_in}}</strong>
                                            </div>
                                        </div>
                                        @if($item->foto_out == null)
                                        <div class="d-flex align-items-center gap-2">
                                            
                                            <div class="text-center pl-1">
                                                <span class="d-block">Pulang</span>
                                                <strong>--:--</strong>
                                            </div>
                                        </div>
                                        @else
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset('storage/absensi/'.$item->pegawai->nip.'/'.$item->foto_out) }}" alt="Foto Masuk" class="rounded" width="50">
                                            <div class="text-center pl-1">
                                                <span class="d-block">Pulang</span>
                                                <strong>{{$item->jam_out}}</strong>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        <ul class="listview image-listview">
                            @foreach($leaderboard as $d)
                            <li>
                                <div class="item ">
                                    <img src="{{ asset('storage/absensi/'.$d->pegawai->nip.'/'.$d->foto_in) }}" alt="image" class="image rounded-circle" width="30">
                                    <div class="in">
                                        <div>
                                            <b>{{ $d->pegawai->nama_lengkap }}</b><br>
                                            <small class="text-muted">{{ $d->pegawai->jabat->jabatan }}</small>
                                        </div>
                                    <span class="badge {{ $d->jam_in < '07:00' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $d->jam_in }}
                                    </span>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
@endsection
