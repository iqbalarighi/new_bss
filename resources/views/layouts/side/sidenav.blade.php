

<div class="bg-light border-right bg-info" id="sidebar-wrapper">
<div class="list-group list-group-flush sticky-top"  style="background-color: darkgrey;">
<div class="sidebar-heading text-center" style="background-color: mediumpurple; ">BSS</div>
{{-- <a href="{{route('master')}}" class="list-group-item list-group-item-action bg-light">Master</a> --}}
<a onclick="cekMaster()" class="list-group-item list-group-item-action bg-light {{ Route::is('tenant')||Route::is('kantor')||Route::is('departemen')|| Route::is('satker')? 'active' : '' }}" data-bs-toggle="collapse"  href="#master" role="button" aria-expanded="false" aria-controls="master">
            Master Data
                    <i id="mas" class="bi bi-caret-right-fill"></i>
          </a>
            <div class="collapse {{ Route::is('tenant')||Route::is('kantor')||Route::is('departemen')|| Route::is('satker')? 'show' : '' }}" id="master">
                <div class="card card-body p-1">
                    <div class="list-group list-group-flush" style="width: 100%;">
                        <a href="{{route('tenant')}}" class="list-group-item list-group-item-action bg-light {{ Route::is('tenant') ? 'active' : '' }}">Tenant</a>
                        <a href="{{route('kantor')}}" class="list-group-item list-group-item-action bg-light {{ Route::is('kantor') ? 'active' : '' }}">Kantor</a>
                        <a href="" class="list-group-item list-group-item-action bg-light {{ Route::is('filemanager') ? 'active' : '' }}">Departemen</a>
                        <a href="" class="list-group-item list-group-item-action bg-light {{ Route::is('useronline') ? 'active' : '' }}">Satuan Kerja</a>
                    </div> 
                </div>
            </div>
    <a href="#" class="list-group-item list-group-item-action bg-light">Karyawan</a>

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