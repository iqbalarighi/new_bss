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
                <div class="row">
                    <div class="col-6">
                        <div class="card gradasigreen">
                            <div class="card-body">
                                {{-- <a href="{{url('/absen/create')}}"> --}}
                                    <div class="presencecontent">
                                        <div class="iconpresence">
                                            <ion-icon name="camera"></ion-icon>
                                        </div>
                                        <div class="presencedetail">
                                            <h4 class="presencetitle">Masuk</h4>
                                            <span>{{$absen->jam_in}}</span>
                                        </div>
                                    </div>
                                {{-- </a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card gradasired">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence">
                                        <ion-icon name="camera"></ion-icon>
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitle">Pulang</h4>
                                        <span>{{$absen->jam_out}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="rekappresence"> --}}
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
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="image-outline" role="img" class="md hydrated"
                                            aria-label="image outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Photos</div>
                                        <span class="badge badge-danger">10</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-secondary">
                                        <ion-icon name="videocam-outline" role="img" class="md hydrated"
                                            aria-label="videocam outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Videos</div>
                                        <span class="text-muted">None</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-danger">
                                        <ion-icon name="musical-notes-outline" role="img" class="md hydrated"
                                            aria-label="musical notes outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Music</div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-danger">
                                        <ion-icon name="musical-notes-outline" role="img" class="md hydrated"
                                            aria-label="musical notes outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Music</div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-danger">
                                        <ion-icon name="musical-notes-outline" role="img" class="md hydrated"
                                            aria-label="musical notes outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Music</div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-danger">
                                        <ion-icon name="musical-notes-outline" role="img" class="md hydrated"
                                            aria-label="musical notes outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Music</div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-danger">
                                        <ion-icon name="musical-notes-outline" role="img" class="md hydrated"
                                            aria-label="musical notes outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Music</div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-danger">
                                        <ion-icon name="musical-notes-outline" role="img" class="md hydrated"
                                            aria-label="musical notes outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Music</div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-danger">
                                        <ion-icon name="musical-notes-outline" role="img" class="md hydrated"
                                            aria-label="musical notes outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Music</div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-danger">
                                        <ion-icon name="musical-notes-outline" role="img" class="md hydrated"
                                            aria-label="musical notes outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Music</div>
                                    </div>
                                </div>
                            </li>
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
