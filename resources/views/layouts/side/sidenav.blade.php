

<div class="bg-light border-right bg-info" id="sidebar-wrapper">
<div class="list-group list-group-flush sticky-top"  style="background-color: darkgrey;">
<div class="sidebar-heading text-center" style="background: linear-gradient(135deg, #8B0000, #FF6347);">BSS</div>
{{-- <a href="{{route('master')}}" class="list-group-item list-group-item-action bg-light">Master</a> --}}
<a onclick="cekMaster()" class="list-group-item list-group-item-action bg-light {{ Route::is('tenant')||Route::is('kantor')||Route::is('satker')|| Route::is('jabatan')? 'active' : '' }}" data-bs-toggle="collapse"  href="#master" role="button" aria-expanded="false" aria-controls="master">
            Master Data
                    <i id="mas" class="bi bi-caret-right-fill"></i>
          </a>
            <div class="collapse {{ Route::is('tenant')||Route::is('kantor')||Route::is('satker')|| Route::is('jabatan')? 'show' : '' }}" id="master">
                <div class="card card-body p-1">
                    <div class="list-group list-group-flush" style="width: 100%;">
                @if(Auth::user()->role == 0)
                        <a href="{{route('tenant')}}" class="list-group-item list-group-item-action bg-light {{ Route::is('tenant') ? 'active' : '' }}">Tenant</a>
                @endif
                        <a href="{{route('kantor')}}" class="list-group-item list-group-item-action bg-light {{ Route::is('kantor') ? 'active' : '' }}">Kantor</a>
                        <a href="{{route('satker')}}" class="list-group-item list-group-item-action bg-light {{ Route::is('satker') ? 'active' : '' }}">Satuan Kerja</a>
                        <a href="{{route('jabatan')}}" class="list-group-item list-group-item-action bg-light {{ Route::is('jabatan') ? 'active' : '' }}">Jabatan</a>
                    </div> 
                </div>
            </div>
    {{-- <a href="{{route('pegawai.index')}}" class="list-group-item list-group-item-action bg-light">Pegawai</a> --}}
    <a onclick="cekPeg()" class="list-group-item list-group-item-action bg-light {{Request::is('pegawai')||Request::is('pegawai/*')? 'active' : ''}}" data-bs-toggle="collapse"  href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        Pegawai 
                <i id="peg" class="bi bi-caret-right-fill"></i>
      </a>
        <div id="collapseExample" class="collapse {{ Request::is('pegawai')||Request::is('pegawai/*')? 'show' : '' }}">
            <div class="card card-body">
                <div class="list-group list-group-flush" style="width: 100%;">
                    <a href="{{route('pegawai.index')}}" class="list-group-item list-group-item-action bg-light {{ Request::is('pegawai')||Request::is('pegawai/input') ? 'active' : '' }}">Daftar Pegawai</a>
                    <a href="#" class="list-group-item list-group-item-action bg-light">Absensi</a>
                    <a href="#" class="list-group-item list-group-item-action bg-light">Insiden/Kejadian</a>
                </div> 
            </div>
        </div>

    <a onclick="cekDown()" class="list-group-item list-group-item-action bg-light" data-bs-toggle="collapse"  href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        Laporan 
                <i id="ubah" class="bi bi-caret-right-fill"></i>
      </a>
        <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <div class="list-group list-group-flush" style="width: 100%;">
                    <a href="#" class="list-group-item list-group-item-action bg-light">Kegiatan</a>
                    <a href="#" class="list-group-item list-group-item-action bg-light">Serah Terima Jaga</a>
                    <a href="#" class="list-group-item list-group-item-action bg-light">Insiden/Kejadian</a>
                    <a href="#" style="cursor: not-allowed; " class="list-group-item list-group-item-action bg-light"><s>Bencana</s></a>
                    <a href="#" style="cursor: not-allowed; " class="list-group-item list-group-item-action bg-light"><s>Temuan Patroli</s></a>
                    {{-- <a href="#" style="cursor: not-allowed; " class="list-group-item list-group-item-action bg-light">Unras</a> --}}
                </div> 
            </div>
        </div>
     <a href="#" class="list-group-item list-group-item-action bg-light">Unras</a>
</div>
</div>