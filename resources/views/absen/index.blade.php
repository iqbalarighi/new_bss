@extends('layouts.absen.absen')
@section('content')
<style>
    #user-role {
  display: block;
  white-space: normal;
  line-height: 1.1;         /* Atur tinggi baris agar cukup ruang antar baris */
  word-wrap: break-word;    /* Agar kata panjang bisa dipotong jika perlu */
}
</style>
    <div class="section p-2" id="user-section" style="z-index: 1;">
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
                    <h2 id="user-name" style="width: 80%;">{{Auth::guard('pegawai')->user()->nama_lengkap}}</h2>
                    <span id="user-role">{{$pegawai->jabat->jabatan}}</span> <br>
                </div>

    {{-- @if(strtolower($pegawai->jabat->jabatan) == 'supervisor' || strtolower($pegawai->jabat->jabatan) == 'danru')
        <div style="position: relative;">
            <button onclick="toggleDropdown()" style="background: none; border: none; font-size: 1.8rem; color: white;">
                <ion-icon name="menu-outline"></ion-icon>
            </button>
            <div id="dropdownMenu" style="display: none; position: absolute; right: 0; background: white; border-radius: 5px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); z-index: 100;">
                <a href="{{ route('absen.lapor') }}" style="display: block; padding: 8px 16px; text-decoration: none; color: black;">Laporan</a>
            </div>
        </div>
    @endif --}}

    </div>
</div>

{{-- @if(strtolower($pegawai->jabat->jabatan) == 'supervisor' || strtolower($pegawai->jabat->jabatan) == 'danru')
    <script>
        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }

        // optional: hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('dropdownMenu');
            const button = e.target.closest('button');
            if (!dropdown.contains(e.target) && !button) {
                dropdown.style.display = 'none';
            }
        });
</script>
@endif --}}



        <div class="section" id="menu-section" style="z-index: 1;">
            <div class="card">
                <div class="card-body text-center p-2">
                    <div class="list-menu">
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="{{ route('absen.profile') }}" class="green" style="font-size: 40px;">
                                    <ion-icon name="person-sharp"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Profil</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="{{ route('absen.histori') }}" class="danger" style="font-size: 40px;">
                                    <ion-icon name="calendar-outline"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Histori</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="{{ route('absen.izin') }}" class="warning" style="font-size: 40px;">
                                    <ion-icon name="document-text"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Izin</span>
                            </div>
                        </div>
                        @php
                            use Illuminate\Support\Str;
                        @endphp

                        @if(Str::contains(strtolower($pegawai->jabat->jabatan), ['supervisor', 'danru', 'kepala', 'koordinator']) )

                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="{{ route('absen.lapor') }}" class="orange" style="font-size: 40px;">
                                    <ion-icon name="newspaper-outline"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                Laporan
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="section mt-2" id="presence-section">
            <div class="todaypresence">
        @if($absen != null)
                    <h5><ion-icon name="person"></ion-icon> Kehadiran Terakhir</h5>
            <div class="card p-1 mb-1">
                <h5>{{Carbon\carbon::parse($absen->tgl_absen)->locale('id')->translatedFormat('l, d M Y')}} ({{$absen->shifts->shift}})</h5>

                <div class="d-flex justify-content-around align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('storage/absensi/'.$absen->pegawai->nip.'/'.$absen->foto_in) }}" alt="Foto Masuk" class="rounded" width="50">

                        <div class="text-center pl-1 {{$absen->jam_in > $absen->shifts->jam_masuk ? 'text-danger' : ''}}">
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

        @if($absenTerakhir)
                    <h5><ion-icon name="person"></ion-icon> Kehadiran Terakhir</h5>
            <div class="card p-1 mb-1">
                <h5>{{Carbon\carbon::parse($absenTerakhir->tgl_absen)->locale('id')->translatedFormat('l, d M Y')}} ({{$absenTerakhir->shifts->shift}})</h5>

                <div class="d-flex justify-content-around align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('storage/absensi/'.$absenTerakhir->pegawai->nip.'/'.$absenTerakhir->foto_in) }}" alt="Foto Masuk" class="rounded" width="50">

                        <div class="text-center pl-1 {{$absenTerakhir->jam_in > $absenTerakhir->shifts->jam_masuk ? 'text-danger' : ''}}">
                            <span class="d-block">Masuk</span>
                            <strong>{{$absenTerakhir->jam_in}}</strong>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        
                        <div class="text-center pl-1">
                            <span class="d-block">Pulang</span>
                            <strong>--:--</strong>
                        </div>
                    </div>
                </div>
                    <div>
                        <span class="text-warning float-right pr-1">Berlangsung</span>
                    </div>

            </div>
        @endif

         @if($lembur != null)
         <h5 class="mt-3"><ion-icon name="time"></ion-icon> Lembur</h5>
            <div class="card p-1 mb-1">
                <h5>{{Carbon\carbon::parse($lembur->tgl_absen)->locale('id')->translatedFormat('l, d M Y')}} <span id="timer" class="float-end pe-2 {{ $lembur->jam_out ? 'text-success' : 'text-primary' }}">00:00:00</span></h5>
                

                <div class="d-flex justify-content-around align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('storage/lembur/'.$lembur->pegawai->nip.'/'.$lembur->foto_in) }}" alt="Foto Masuk" class="rounded" width="50">

                        <div class="text-center pl-1 ">
                            <span class="d-block">Mulai</span>
                            <strong>{{ Carbon\Carbon::parse($lembur->jam_in)->format('H:i:s') }}</strong>
                        </div>
                    </div>
                    @if($lembur->jam_out == null)
                    <div class="d-flex align-items-center gap-2">
                        
                        <div class="text-center pl-1">
                            <span class="d-block">Selesai</span>
                            <strong>--:--</strong>
                        </div>
                    </div>
                    @else
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('storage/lembur/'.$lembur->pegawai->nip.'/'.$lembur->foto_out) }}" alt="Foto Masuk" class="rounded" width="50">
                        <div class="text-center pl-1">
                            <span class="d-block">Selesai</span>
                            <strong>{{$lembur->jam_out}}</strong>
                        </div>
                    </div>
                    @endif
                </div>
                    @if($lembur->jam_out == null)
                    <div>
                        <span class="text-warning float-right pr-1">Berlangsung</span>
                    </div>
                    @endif
            </div>
        @endif

         @if($ceklem != null)
         <h5 class="mt-3"><ion-icon name="time"></ion-icon> Lembur</h5>
            <div class="card p-1 mb-1">
                <h5>{{Carbon\carbon::parse($ceklem->tgl_absen)->locale('id')->translatedFormat('l, d M Y')}} <span id="timer" class="float-end pe-2 {{ $ceklem->jam_out ? 'text-success' : 'text-primary' }}">00:00:00</span></h5>
                

                <div class="d-flex justify-content-around align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('storage/lembur/'.$ceklem->pegawai->nip.'/'.$ceklem->foto_in) }}" alt="Foto Masuk" class="rounded" width="50">

                        <div class="text-center pl-1 ">
                            <span class="d-block">Mulai</span>
                            <strong>{{ Carbon\Carbon::parse($ceklem->jam_in)->format('H:i:s') }}</strong>
                        </div>
                    </div>
                    @if($ceklem->jam_out == null)
                    <div class="d-flex align-items-center gap-2">
                        
                        <div class="text-center pl-1">
                            <span class="d-block">Selesai</span>
                            <strong>--:--</strong>
                        </div>
                    </div>
                    @else
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ asset('storage/lembur/'.$ceklem->pegawai->nip.'/'.$ceklem->foto_out) }}" alt="Foto Masuk" class="rounded" width="50">
                        <div class="text-center pl-1">
                            <span class="d-block">Selesai</span>
                            <strong>{{ Carbon\Carbon::parse($ceklem->jam_out)->format('H:i:s') }}</strong>
                        </div>
                    </div>
                    @endif
                </div>
                    @if($ceklem->jam_out == null)
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
                                @if ($rekapizin->cuti != null)
                                <div class="badge-presensi">{{$rekapizin->cuti}}</div>
                                @endif
                                <ion-icon name="airplane-outline" size="large" style="color: greenyellow;"></ion-icon>
                                <p class="mb-0 text-dark">Cuti</p>
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
                                <h5>{{Carbon\carbon::parse($item->tgl_absen)->locale('id')->translatedFormat('l, d M Y')}} ({{$item->shifts->shift}})</h5>
                                <div class="d-flex justify-content-around align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ asset('storage/absensi/'.$item->pegawai->nip.'/'.$item->foto_in) }}" alt="Foto Masuk" class="rounded" width="50">
                                        <div class="text-center pl-1 {{$item->jam_in > $item->shifts->jam_masuk ? 'text-danger' : ''}}">
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
                                <span class="badge {{ $d->jam_in < $d->shifts->jam_masuk ? 'bg-success' : 'bg-danger' }}">
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

@push('myscript')
@if($lembur != null)
<script>
    const jamIn = new Date("{{ \Carbon\Carbon::parse($lembur->jam_in)->timezone('Asia/Jakarta')->format('Y-m-d\TH:i:sP') }}");
    const jamOutRaw = @json($lembur->jam_out ? \Carbon\Carbon::parse($lembur->jam_out)->timezone('Asia/Jakarta')->format('Y-m-d\TH:i:sP') : null);
    const jamOut = jamOutRaw ? new Date(jamOutRaw) : null;

    function updateTimer() {
        const now = new Date();
        const end = jamOut || now;

        let diff = Math.floor((end - jamIn) / 1000);
        if (diff < 0) diff = 0;

        const hours = String(Math.floor(diff / 3600)).padStart(2, '0');
        const minutes = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
        const seconds = String(diff % 60).padStart(2, '0');

        document.getElementById('timer').innerText = `${hours}:${minutes}:${seconds}`;
    }

    updateTimer();

    if (!jamOut) {
        setInterval(updateTimer, 1000); // update tiap detik kalau jamOut belum ada
    }
</script>
@endif
@if($ceklem != null)
<script>
    // Pastikan waktu menggunakan zona lokal yang benar (WIB)
    const jamIn = new Date("{{ \Carbon\Carbon::parse($ceklem->jam_in)->timezone('Asia/Jakarta')->format('Y-m-d\TH:i:sP') }}");
    const jamOutRaw = @json($ceklem->jam_out ? \Carbon\Carbon::parse($ceklem->jam_out)->timezone('Asia/Jakarta')->format('Y-m-d\TH:i:sP') : null);
    const jamOut = jamOutRaw ? new Date(jamOutRaw) : null;

    function updateTimer() {
        const now = new Date();
        const end = jamOut || now;

        let diff = Math.floor((end - jamIn) / 1000);
        if (diff < 0) diff = 0;

        const hours = String(Math.floor(diff / 3600)).padStart(2, '0');
        const minutes = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
        const seconds = String(diff % 60).padStart(2, '0');

        document.getElementById('timer').innerText = `${hours}:${minutes}:${seconds}`;
    }

    updateTimer();

    if (!jamOut) {
        setInterval(updateTimer, 1000); // update tiap detik kalau jamOut belum ada
    }
</script>
@endif
@if($absenTerakhir && is_null($absenTerakhir->jam_out))
    <script type="text/javascript">
        Swal.fire({
            icon: 'info',
            title: 'Perhatian!',
            text: 'Absen Terakhir belum selesai, Selesaikan Absen!',
            showCancelButton: true,
            confirmButtonText: 'Absen',
            cancelButtonText: 'Nanti',
            reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('absen.create') }}'; // lanjut absen walau di luar radius
                    }
                });
    </script>
@endif
@endpush