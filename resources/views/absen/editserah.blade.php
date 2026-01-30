@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color:#ef3b3b;">
    <div class="left">
        <a href="{{ route('tukar-jaga.show', $detail->id) }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Edit Serah Terima Jaga</div>
</div>
@endsection

@section('content')

<div class="container" style="margin-top:4rem; margin-bottom:5rem;">
    <div class="card">
        <div class="card-header fw-bold">Edit Laporan</div>

        <div class="card-body d-flex justify-content-center" style="overflow:auto; ">

            <form action="{{ route('tukar-jaga.update', $detail->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control"
                        value="{{ $detail->tanggal }}">
                </div>

                <div class="form-group mt-2">
                    <label>Shift</label>
                    <select name="shift" class="form-control">
                        <option value="Pagi" {{ $detail->shift=='Pagi'?'selected':'' }}>Pagi</option>
                        <option value="Malam" {{ $detail->shift=='Malam'?'selected':'' }}>Malam</option>
                    </select>
                </div>

                <div class="form-group mt-2">
                    <label>Lokasi Gedung</label>
                    <input type="text" class="form-control"
                           value="{{ $detail->lokasi_gedung }}" readonly>
                </div>

                <div class="form-group mt-2">
                    <label>Shift Lama</label>
                    <textarea name="petugas_lama" class="form-control">{{ $detail->petugas_lama }}</textarea>
                </div>

                <div class="form-group mt-2">
                    <label>Shift Baru</label>
                    <textarea name="petugas_baru" class="form-control">{{ $detail->petugas_baru }}</textarea>
                </div>

                <hr>

                <h6>Serah Terima Barang</h6>

                <div id="barang-wrapper">

                    @foreach($detail->barang as $b)
                    <div class="row mb-2">
                        <div class="col px-0">
                            <input type="text" class="form-control" name="nama_barang[]"
                                value="{{ $b->nama_barang }}" placeholder="Nama barang">
                        </div>
                        <div class="col-2 px-1">
                            <input
                                type="text"
                                class="form-control"
                                name="jumlah[]"
                                placeholder="Jumlah"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                value="{{ $b->jumlah }}" 
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            >
                        </div>
                        <div class="col px-0">
                            <input type="text" class="form-control" name="kondisi[]"
                                value="{{ $b->kondisi }}" placeholder="Kondisi">
                        </div>
                    </div>
                    @endforeach

                </div>

                <button type="button" class="btn btn-secondary btn-sm" onclick="addBarang()">+ Tambah Barang</button>

                <div class="form-group mt-3">
                    <label>Kejadian Selama Jaga</label>
                    <textarea name="kejadian" class="form-control">{{ $detail->kejadian }}</textarea>
                </div>

                <button class="btn btn-danger btn-block mt-3" type="submit">
                    Update
                </button>

            </form>
        </div>
    </div>
</div>

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
    </div>
    `;
    document.getElementById('barang-wrapper').insertAdjacentHTML('beforeend', html);
}
</script>

@endsection
