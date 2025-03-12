
<div class="bg-light border-right bg-info" id="sidebar-wrapper" style="max-width: auto;">
    <div class="list-group list-group-flush sticky-top" style="background-color: darkgrey;">
        <div class="sidebar-heading text-center text-white" style="background: linear-gradient(135deg, #8B0000, #FF6347);">BPBSmartSystem</div>
        
        <?php $masterActive = request()->is('tenant') ||request()->is('adduser') || request()->is('kantor') || request()->is('satker') || request()->is('jabatan'); ?>
        <a class="list-group-item list-group-item-action bg-light {{ $masterActive ? 'active' : '' }}" style="width: 100%;" data-bs-toggle="collapse" href="#master" role="button" aria-expanded="false" aria-controls="master" onclick="toggleIcon('mas')">
            Master Data <i id="mas" class="bi {{ $masterActive ? 'bi-caret-down-fill' : 'bi-caret-right-fill' }}"></i>
        </a>
        <div class="collapse {{ $masterActive ? 'show' : '' }}" id="master">
            <div class="card card-body p-1">
                <div class="list-group list-group-flush">
                    @if(Auth::user()->role == 0)
                        <a href="{{ route('tenant') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('tenant') ? 'active' : '' }}" style="width: 95%;">Tenant</a>
                        <a href="{{ route('adduser') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('adduser') ? 'active' : '' }}" style="width: 95%;">Tambah User</a>
                    @endif
                    <a href="{{ route('kantor') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('kantor') ? 'active' : '' }}" style="width: 95%;">Kantor</a>
                    <a href="{{ route('satker') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('satker') ? 'active' : '' }}" style="width: 95%;">Satuan Kerja</a>
                    <a href="{{ route('jabatan') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('jabatan') ? 'active' : '' }}" style="width: 95%;">Jabatan</a>
                </div>
            </div>
        </div>

        <?php $pegawaiActive = request()->is('pegawai*'); ?>
        <a class="list-group-item list-group-item-action bg-light {{ $pegawaiActive ? 'active' : '' }}" style="width: 100%;" data-bs-toggle="collapse" href="#pegawai" role="button" aria-expanded="false" aria-controls="pegawai" onclick="toggleIcon('peg')">
            Pegawai <i id="peg" class="bi {{ $pegawaiActive ? 'bi-caret-down-fill' : 'bi-caret-right-fill' }}"></i>
        </a>
        <div class="collapse {{ $pegawaiActive ? 'show' : '' }}" id="pegawai">
            <div class="card card-body p-1">
                <div class="list-group list-group-flush">
                    <a href="{{ route('pegawai.index') }}" class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai') ? 'active' : '' }}" style="width: 95%;">Daftar Pegawai</a>
                    <a href="#" class="list-group-item list-group-item-action bg-light" style="width: 95%;">Absensi</a>
                    <a href="#" class="list-group-item list-group-item-action bg-light" style="width: 95%;">Insiden/Kejadian</a>
                </div>
            </div>
        </div>

        <?php $laporanActive = request()->is('laporan*'); ?>
        <a class="list-group-item list-group-item-action bg-light {{ $laporanActive ? 'active' : '' }}" style="width: 100%;" data-bs-toggle="collapse" href="#laporan" role="button" aria-expanded="false" aria-controls="laporan" onclick="toggleIcon('ubah')">
            Laporan <i id="ubah" class="bi {{ $laporanActive ? 'bi-caret-down-fill' : 'bi-caret-right-fill' }}"></i>
        </a>
        <div class="collapse {{ $laporanActive ? 'show' : '' }}" id="laporan">
            <div class="card card-body p-1">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action bg-light" style="width: 95%;">Kegiatan</a>
                    <a href="#" class="list-group-item list-group-item-action bg-light" style="width: 95%;">Serah Terima Jaga</a>
                    <a href="#" class="list-group-item list-group-item-action bg-light" style="width: 95%;">Insiden/Kejadian</a>
                    <a href="#" class="list-group-item list-group-item-action bg-light" style="width: 95%; cursor: not-allowed;"><s>Bencana</s></a>
                    <a href="#" class="list-group-item list-group-item-action bg-light" style="width: 95%; cursor: not-allowed;"><s>Temuan Patroli</s></a>
                </div>
            </div>
        </div>
        
        <a href="#" class="list-group-item list-group-item-action bg-light" style="width: 100%;">Unras</a>
    </div>
</div>

<script>
    function toggleIcon(iconId) {
        var icon = document.getElementById(iconId);
        icon.classList.toggle("bi-caret-right-fill");
        icon.classList.toggle("bi-caret-down-fill");
    }
</script>
