
<div class="bg-light border-right bg-info" id="sidebar-wrapper" style="max-width: auto;">
    <div class="list-group list-group-flush sticky-top" style="background-color: darkgrey;">
        <div class="sidebar-heading text-center text-white" style="background: linear-gradient(135deg, #8B0000, #FF6347);">BPBSmartSystem</div>
@if(Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 3)
        <?php $masterActive = request()->is('tenant') ||request()->is('users') || request()->is('kantor') || request()->is('satker') || request()->is('jabatan') || request()->is('shift') || request()->is('departemen'); ?>
        <a class="list-group-item list-group-item-action bg-light {{ $masterActive ? 'active' : '' }}" style="width: 100%;" data-bs-toggle="collapse" href="#master" role="button" aria-expanded="false" aria-controls="master" onclick="toggleIcon('mas')">
            Master Data <i id="mas" class="bi {{ $masterActive ? 'bi-caret-down-fill' : 'bi-caret-right-fill' }}"></i>
        </a>
        <div class="collapse {{ $masterActive ? 'show' : '' }}" id="master">
            <div class="card card-body p-1">
                <div class="list-group list-group-flush">
                    @if(Auth::user()->role == 0)
                        <a href="{{ route('tenant') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('tenant') ? 'active' : '' }}" style="width: 95%;">Tenant</a>
@endif
    @if(Auth::user()->role == 1 || Auth::user()->role == 0)
                    <a href="{{ route('kantor') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('kantor') ? 'active' : '' }}" style="width: 95%;">Kantor</a>
        @endif

    @if(Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 3)
                    <a href="{{ route('departemen') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('departemen') ? 'active' : '' }}" style="width: 95%;">Departemen</a>
                    <a href="{{ route('satker') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('satker') ? 'active' : '' }}" style="width: 95%;">Satuan Kerja</a>
                    <a href="{{ route('jabatan') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('jabatan') ? 'active' : '' }}" style="width: 95%;">Jabatan</a>
                    <a href="{{ route('shift') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('shift') ? 'active' : '' }}" style="width: 95%;">Shift</a>
                    <a href="{{ route('users') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('users') ? 'active' : '' }}" style="width: 95%;">Manage User</a>
        @endif
                </div>
            </div>
        </div>
    @endif

    @if(Auth::user()->role == 1 || Auth::user()->role == 0|| Auth::user()->role == 3)
        <a href="{{route('home')}}" class="list-group-item list-group-item-action bg-light {{ request()->is('home') ? 'active' : '' }}" style="width: 100%;">Dashboard</a>

        <?php $pegawaiActive = request()->is('pegawai*'); ?>
        <a class="list-group-item list-group-item-action bg-light {{ $pegawaiActive ? 'active' : '' }}" style="width: 100%;" data-bs-toggle="collapse" href="#pegawai" role="button" aria-expanded="false" aria-controls="pegawai" onclick="toggleIcon('peg')">
            Pegawai <i id="peg" class="bi {{ $pegawaiActive ? 'bi-caret-down-fill' : 'bi-caret-right-fill' }}"></i>
        </a>
        <div class="collapse {{ $pegawaiActive ? 'show' : '' }}" id="pegawai">
            <div class="card card-body p-1">
                <div class="list-group list-group-flush">
                    <a href="{{ route('pegawai.index') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai') ? 'active' : '' }}" style="width: 95%;">Daftar Pegawai</a>
                    <a href="{{ route('pegawai.absensi') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai/absensi') ? 'active' : '' }}" style="width: 95%;">Absensi</a>
                    <a href="{{ route('pegawai.absensi.izin') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai/absensi/izin') ? 'active' : '' }}" style="width: 95%;">Izin</a>
                    <a href="{{ route('pegawai.absensi.laporan') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai/absensi/laporan') ? 'active' : '' }}" style="width: 95%;">Laporan Absensi</a>
                    <a href="{{ route('pegawai.absensi.rekap') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai/absensi/rekap') ? 'active' : '' }}" style="width: 95%;">Rekap Absensi</a>
                </div>
            </div>
        </div>
    @endif

{{-- @if(Auth::user()->role == 2 || Auth::user()->role == 1 || Auth::user()->role == 0) --}}
        <?php $laporanActive = request()->is('laporan*'); ?>
        <a class="list-group-item list-group-item-action bg-light {{ $laporanActive ? 'active' : '' }}"
           style="width: 100%;" data-bs-toggle="collapse" href="#laporan" role="button"
           aria-expanded="{{ $laporanActive ? 'true' : 'false' }}" aria-controls="laporan"
           onclick="toggleIcon('ubah')">
            Laporan <i id="ubah" class="bi {{ $laporanActive ? 'bi-caret-down-fill' : 'bi-caret-right-fill' }}"></i>
        </a>

        <div class="collapse {{ $laporanActive ? 'show' : '' }}" id="laporan">
            <div class="card card-body p-1">
                <div class="list-group list-group-flush">
                    @foreach($satkers as $satker)
                        <a href="{{ route('laporan.satker', $satker->id) }}"
                           class="list-group-item list-group-item-action bg-light {{ request()->is('laporan/'.$satker->id) ? 'active' : '' }}"
                           style="width: 95%; font-size: 11pt;">
                             @if(Auth::user()->role == 0 || Auth::user()->role == 1){{$satker->kant->nama_kantor}}->@endif{{ $satker->satuan_kerja }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

{{-- @endif --}}
        {{-- <a href="#" class="list-group-item list-group-item-action bg-light" style="width: 100%;">Unras</a> --}}
    </div>
</div>

<script>
    function toggleIcon(iconId) {
        var icon = document.getElementById(iconId);
        icon.classList.toggle("bi-caret-right-fill");
        icon.classList.toggle("bi-caret-down-fill");
    }
</script>
