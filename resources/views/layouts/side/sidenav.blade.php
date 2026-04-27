<div class="bg-light border-right bg-info" id="sidebar-wrapper">
    <div class="list-group list-group-flush sticky-top" style="background-color: darkgrey;">
        
        <div class="sidebar-heading text-center text-white"
             style="background: linear-gradient(135deg, #8B0000, #FF6347);">
            BPBSmartSystem
        </div>

        {{-- ================= MASTER DATA ================= --}}
        @if(Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 3)

        <?php 
        $masterActive = request()->is('tenant') 
            || request()->is('users') 
            || request()->is('checkpoints') 
            || request()->is('kantor') 
            || request()->is('satker') 
            || request()->is('jabatan') 
            || request()->is('shift') 
            || request()->is('departemen')
            || request()->is('regu')
            // || request()->is('pengecualian-absen*'); // ✅ SUPPORT ROUTE KAMU
        ?>

        <a class="list-group-item list-group-item-action bg-light {{ $masterActive ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#master"
           onclick="toggleIcon('mas')">

            Master Data
            <i id="mas" class="bi {{ $masterActive ? 'bi-caret-down-fill' : 'bi-caret-right-fill' }}"></i>
        </a>

        <div class="collapse {{ $masterActive ? 'show' : '' }}" id="master">
    <div class="card card-body p-1">
        <div class="list-group list-group-flush">

            @if(Auth::user()->role == 0)
                <a href="{{ route('tenant') }}"
                   class="list-group-item list-group-item-action bg-light {{ request()->is('tenant') ? 'active' : '' }}">
                   Tenant
                </a>
            @endif

            @if(Auth::user()->role == 1 || Auth::user()->role == 0)
                <a href="{{ route('kantor') }}"
                   class="list-group-item list-group-item-action bg-light {{ request()->is('kantor') ? 'active' : '' }}">
                   Kantor
                </a>
            @endif

            <a href="{{ route('departemen') }}"
               class="list-group-item list-group-item-action bg-light {{ request()->is('departemen') ? 'active' : '' }}">
               Departemen
            </a>

            <a href="{{ route('satker') }}"
               class="list-group-item list-group-item-action bg-light {{ request()->is('satker') ? 'active' : '' }}">
               Satuan Kerja
            </a>

            <a href="{{ route('jabatan') }}"
               class="list-group-item list-group-item-action bg-light {{ request()->is('jabatan') ? 'active' : '' }}">
               Jabatan
            </a>

            <a href="{{ route('shift') }}"
               class="list-group-item list-group-item-action bg-light {{ request()->is('shift') ? 'active' : '' }}">
               Shift
            </a>

            <!-- ✅ TAMBAHAN REGU -->
            <a href="{{ route('regu') }}"
               class="list-group-item list-group-item-action bg-light {{ request()->is('regu') ? 'active' : '' }}">
               Regu Pengamanan
            </a>

            <a href="{{ route('checkpoints.index') }}"
               class="list-group-item list-group-item-action bg-light {{ request()->is('checkpoints') ? 'active' : '' }}">
               Area Patroli
            </a>

            <a href="{{ route('users') }}"
               class="list-group-item list-group-item-action bg-light {{ request()->is('users') ? 'active' : '' }}">
               Manage User
            </a>

        </div>
    </div>
</div>
        @endif

        {{-- ================= DASHBOARD ================= --}}
        @if(Auth::user()->role == 1 || Auth::user()->role == 0 || Auth::user()->role == 3)
        <a href="{{ route('home') }}"
           class="list-group-item list-group-item-action bg-light {{ request()->is('home') ? 'active' : '' }}">
           Dashboard
        </a>

        {{-- ================= PEGAWAI ================= --}}
        <?php 
            $pegawaiActive = request()->is([
                'pegawai*',
                'pengecualian-absen*'
            ]); 
            ?>

        <a class="list-group-item list-group-item-action bg-light {{ $pegawaiActive ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#pegawai"
           onclick="toggleIcon('peg')">

            Pegawai
            <i id="peg" class="bi {{ $pegawaiActive ? 'bi-caret-down-fill' : 'bi-caret-right-fill' }}"></i>
        </a>

        <div class="collapse {{ $pegawaiActive ? 'show' : '' }}" id="pegawai">
            <div class="card card-body p-1">
                <div class="list-group list-group-flush">

                    <a href="{{ route('pegawai.index') }}"
                       class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai') ? 'active' : '' }}">
                       Daftar Pegawai
                    </a>

                    <a href="{{ route('pegawai.absensi') }}"
                       class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai/absensi') ? 'active' : '' }}">
                       Absensi
                    </a>
                    
                    <a href="{{ url('/pengecualian-absen') }}"
                       class="list-group-item list-group-item-action bg-light {{ request()->is('pengecualian-absen*') ? 'active' : '' }}">
                       Pengecualian Absen
                    </a>

                    <a href="{{ route('pegawai.lembur') }}"
                       class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai/lembur') ? 'active' : '' }}">
                       Lembur
                    </a>

                    <a href="{{ route('pegawai.absensi.izin') }}"
                       class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai/absensi/izin') ? 'active' : '' }}">
                       Izin
                    </a>

                    <a href="{{ route('pegawai.absensi.laporan') }}"
                       class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai/absensi/laporan') ? 'active' : '' }}">
                       Laporan Absensi
                    </a>

                    <a href="{{ route('pegawai.absensi.rekap') }}"
                       class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai/absensi/rekap') ? 'active' : '' }}">
                       Rekap Absensi
                    </a>

                    <a href="{{ route('pegawai.lembur.laporan') }}"
                       class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai/lembur/laporan') ? 'active' : '' }}">
                       Laporan Lembur
                    </a>

                    <a href="{{ route('pegawai.lembur.rekap') }}"
                       class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai/lembur/rekap') ? 'active' : '' }}">
                       Rekap Lembur
                    </a>

                    <a href="{{ route('pegawai.patrol') }}"
                       class="list-group-item list-group-item-action bg-light {{ request()->is('pegawai/patroli') ? 'active' : '' }}">
                       Patroli
                    </a>

                </div>
            </div>
        </div>
        @endif

        {{-- ================= LAPORAN ================= --}}
        <?php $laporanActive = request()->is('laporan*'); ?>

        <a class="list-group-item list-group-item-action bg-light {{ $laporanActive ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#laporan"
           onclick="toggleIcon('ubah')">

            Laporan
            <i id="ubah" class="bi {{ $laporanActive ? 'bi-caret-down-fill' : 'bi-caret-right-fill' }}"></i>
        </a>

        <div class="collapse {{ $laporanActive ? 'show' : '' }}" id="laporan">
            <div class="card card-body p-1">
                <div class="list-group list-group-flush">

                    @foreach($satkers as $satker)
                        <a href="{{ route('laporan.satker', $satker->id) }}"
                           class="list-group-item list-group-item-action bg-light {{ request()->is('laporan/'.$satker->id) ? 'active' : '' }}"
                           style="font-size: 11pt;">

                           @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                               {{ $satker->kant->nama_kantor }} ->
                           @endif

                           {{ $satker->satuan_kerja }}
                        </a>
                    @endforeach

                </div>
            </div>
        </div>

    </div>
</div>

<script>
function toggleIcon(iconId) {
    var icon = document.getElementById(iconId);
    icon.classList.toggle("bi-caret-right-fill");
    icon.classList.toggle("bi-caret-down-fill");
}
</script>