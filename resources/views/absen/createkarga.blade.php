@extends('layouts.absen.absen')
@section('header')
<div class="appHeader text-light" style="background-color:#ef3b3b;">
    <div class="left">
        <a href="{{ route('tukar-jaga.index') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Form Serah Terima Jaga</div>
</div>
@endsection
@section('content')
@if (Session::get('success'))
<script>
Swal.fire({
    icon: "success",
    title: "{{ Session::get('success') }}",
    showConfirmButton: true,
});
</script>
@endif

@if(Session::get('error'))
<script>
Swal.fire({
    icon: "error",
    title: "Peringatan",
    text: "{{ session('error') }}",
});
</script>
@endif

<div class="container" style="margin-top:4rem; margin-bottom:5rem;">
    <div class="card">
        <div class="card-header fw-bold">Laporan Tukar Jaga Satpam</div>

        <div class="card-body d-flex justify-content-center" style="overflow:auto; ">
            <form action="{{ route('tukar-jaga.store') }}" method="POST" enctype="multipart/form-data">
                @csrf


                {{-- tanggal --}}
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                </div>

                {{-- shift --}}
                <div class="form-group mt-2">
                    <label>Shift</label>
                    <select name="shift" class="form-control" required="">
                        <option value="" selected ="">Pilih Shift</option>
                        <option value="Pagi">Pagi</option>
                        <option value="Malam">Malam</option>
                    </select>
                </div>

                {{-- lokasi gedung (readonly) --}}
                <div class="form-group mt-2">
                    <label>Lokasi Gedung</label>
                    <input type="text" class="form-control"
                        value="{{ $kantor->nama_kantor ?? '-' }}" readonly>
                </div>

                <div class="mb-2">
                    <label>Petugas Shift Lama</label>
                    <textarea class="form-control" name="petugas_lama" rows="5" required>Danru : {{$user->nama_lengkap}}
Anggota : </textarea>
                </div>

                <div class="mb-2">
                    <label>Petugas Shift Baru</label>
                    <textarea class="form-control" name="petugas_baru" rows="5" required>Danru : 
Anggota : </textarea>
                </div>

                <hr>

                <h6>Serah Terima Barang</h6>

                <div id="barang-wrapper">

                    <div class="row mb-2">
                        <div class="col px-0">
                            <input type="text" class="form-control" name="nama_barang[]" placeholder="Nama barang" required>
                        </div>
                        <div class="col-2 px-1">
                            <input
                                type="text"
                                class="form-control"
                                name="jumlah[]"
                                placeholder="Jumlah"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            >
                        </div>

                        <div class="col px-0">
                            <input type="text" class="form-control" name="kondisi[]" placeholder="Kondisi">
                        </div>
                    </div>

                </div>

                <button type="button" class="btn btn-sm btn-secondary mb-2" onclick="addBarang()">+ Tambah Barang</button>

                <div class="mb-2">
                    <label>Kejadian Selama Jaga</label>
                    <textarea class="form-control" name="kejadian" rows="4"></textarea>
                </div>

                <button class="btn btn-danger btn-block mt-3" type="submit">
                    Simpan
                </button>

            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function addBarang() {
    let html = `
    <div class="row mb-2">
        <div class="col px-0">
            <input type="text" class="form-control" name="nama_barang[]" placeholder="Nama barang">
        </div>
        <div class="col-2 px-1">
            <input
                type="text"
                class="form-control"
                name="jumlah[]"
                placeholder="Jumlah"
                inputmode="numeric"
                pattern="[0-9]*"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            >
        </div>
        <div class="col px-0">
            <input type="text" class="form-control" name="kondisi[]" placeholder="Kondisi">
        </div>
    </div>`;
    document.getElementById('barang-wrapper').insertAdjacentHTML('beforeend', html);
}
</script>
@endsection
