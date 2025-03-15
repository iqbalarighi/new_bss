@extends('layouts.side.side')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header">{{ __('Daftar Satuan Kerja') }}
                    <button class="btn btn-sm btn-primary float-right" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-building-add"></i></button>
                </div>

                <div class="card-body">
@if (Session::get('status'))
<script>
        Swal.fire({
          title: "Berhasil",
          icon: "success",
          showConfirmButton: false,
          timer: 1500
        });
</script>
@endif

<style>
        .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease-out;
        }
        .modal.show .modal-dialog {
            transform: scale(1);
        }

        th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
    <!-- Modal Bootstrap -->                    
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered animate__animated animate__zoomIn">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Satuan Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/satker/tambah" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="satker" class="form-label">Satuan Kerja</label>
                            <input type="text" class="form-control" id="satker" name="satker" placeholder="Satuan Kerja" required>
                        </div>
                        @if(Auth::user()->role == 0)
                        <div class="mb-3">
                            <label for="tenantName" class="form-label">Nama Perusahaan</label>
                            {{-- <input type="text" class="form-control"name="usaha" placeholder="Masukkan nama kantor" required> --}}
                            <select name="perusahaan" id="tenantName" class="form-select" required>
                                <option selected disabled value="">Pilih Perusahaan</option>
                                @foreach($perusahaan as $usaha)
                                <option value="{{$usaha->id}}">{{$usaha->perusahaan}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submmit" class="btn btn-primary">Simpan</button>
                </div> 
            </form>
            </div>
        </div>
    </div>
    <!-- Modal Bootstrap -->
    <div>
            <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                @if(Auth::user()->role == 0)       
                    <th>Perusahaan</th>
                @endif     
                    <th>Satuan Kerja</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($satker as $key => $ker)
                <tr>
                    <td class="align-middle text-center">{{$satker->firstitem()+$key}}</td>
                 @if(Auth::user()->role == 0)
                    <td>{{$ker->perusa->perusahaan}}</td>
                @endif 
                    <td>{{$ker->satuan_kerja}}</td>
                    <td class="align-middle text-center">
                        <button class="btn btn-primary btn-sm cen">Edit</button>
                        <button class="btn btn-danger btn-sm cen">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
         <div class="d-flex justify-content-center">
                {{ $satker->links('pagination::bootstrap-5') }}
            </div>
    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection