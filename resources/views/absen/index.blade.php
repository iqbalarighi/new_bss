@extends('layouts.absen.absen')
@section('content')
    <div class="section" id="user-section">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <button class="btn btn-danger p-1 float-right mt-2">logout</button>
            </a>
            <form id="logout-form" action="{{ route('pegawai.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <div id="user-detail">
                <div class="avatar">
                    @if($pegawai->foto == null)
                        <img src="https://ui-avatars.com/api/?name={{$pegawai->nama_lengkap}}" alt="avatar" class="imaged w64 rounded">
                    @else
                        <img src={{asset('storage/'.$pegawai->foto)}} alt="avatar" class="imaged w64 rounded">
                    @endif
                </div>
                <div id="user-info">
                    <h2 id="user-name">{{Auth::guard('pegawai')->user()->nama_lengkap}}</h2>
                    <span id="user-role">{{$pegawai->jabat->jabatan}}</span>
                </div>
            </div>
        </div>

        <div class="section" id="menu-section">
            <div class="card">
                <div class="card-body text-center">
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
                    <h4><i class="bi bi-lock"></i> Kehadiran Terakhir</h4>
        @if($absen != null)
        <div class="card p-1 mb-1">
            <h5>{{Carbon\carbon::parse($absen->tgl_absen)->locale('id')->translatedFormat('l, d M Y')}}</h5>
            <div class="d-flex justify-content-around align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('storage/absensi/'.$absen->nip.'/'.$absen->foto_in) }}" alt="Foto Masuk" class="rounded" width="50">
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
                    <img src="{{ asset('storage/absensi/'.$absen->nip.'/'.$absen->foto_out) }}" alt="Foto Masuk" class="rounded" width="50">
                    <div class="text-center pl-1">
                        <span class="d-block">Pulang</span>
                        <strong>{{$absen->jam_out}}</strong>
                    </div>
                </div>
                @endif
            </div>
        </div>
            <span class="text-warning mt-2">Berlangsung</span>
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
                                            <img src="{{ asset('storage/absensi/'.$item->nip.'/'.$item->foto_in) }}" alt="Foto Masuk" class="rounded" width="50">
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
                                            <img src="{{ asset('storage/absensi/'.$item->nip.'/'.$item->foto_out) }}" alt="Foto Masuk" class="rounded" width="50">
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
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>Edward Lindgren</div>
                                        <span class="text-muted">Designer</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>Emelda Scandroot</div>
                                        <span class="badge badge-primary">3</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>Henry Bove</div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>Henry Bove</div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>Henry Bove</div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
@endsection
