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
    <!-- Modal Tambah Bootstrap -->                    
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
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div> 
            </form>
            </div>
        </div>
    </div>
    <!-- Modal Edit Bootstrap -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered animate__animated animate__zoomIn">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Satuan Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_satker" class="form-label">Satuan Kerja</label>
                            <input type="text" class="form-control" id="edit_satker" name="satker" required>
                        </div>
                        @if(Auth::user()->role == 0)
                        <div class="mb-3">
                            <label for="edit_tenantName" class="form-label">Nama Perusahaan</label>
                            <select name="perusahaan" id="edit_tenantName" class="form-select" required>
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
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div> 
            </form>
            </div>
        </div>
    </div>
    <!-- Modal Edit Bootstrap -->

    
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
                <tr id="row-{{$ker->id}}">
                    <td class="align-middle text-center">{{$satker->firstitem()+$key}}</td>
                 @if(Auth::user()->role == 0)
                    <td>{{$ker->perusa->perusahaan}}</td>
                @endif 
                    <td>{{$ker->satuan_kerja}}</td>
                    <td class="align-middle text-center">
                        <button class="btn btn-primary btn-sm cen edit-btn" 
                        data-id="{{$ker->id}}" 
                        data-satker="{{$ker->satuan_kerja}}" 
@if(Auth::user()->role == 0) data-perusahaan="{{$ker->perusahaan}}" @endif>Edit</button>
                        <button class="btn btn-danger btn-sm cen delete-btn" data-id="{{$ker->id}}">Hapus</button>
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let satker = this.getAttribute("data-satker");
                let perusahaan = this.getAttribute("data-perusahaan");
                
                document.getElementById("edit_id").value = id;
                document.getElementById("edit_satker").value = satker;
@if(Auth::user()->role == 0) document.getElementById("edit_tenantName").value = perusahaan; @endif
                document.getElementById("editForm").action = "/satker/edit/" + id;

                let editModal = new bootstrap.Modal(document.getElementById("editModal"));
                editModal.show();
            });
        });
    });
</script>
<script>
document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!",

                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Menghapus...",
                            text: "Mohon tunggu",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        fetch("/satker/hapus/" + id, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire("Terhapus!", "Data telah dihapus.", "success");
                                document.getElementById("row-" + id).remove();
                            } else {
                                Swal.fire("Gagal!", "Terjadi kesalahan.", "error");
                            }
                        })
                        .catch(error => {
                            Swal.fire("Error!", "Gagal menghapus data.", "error");
                        });
                    }
                });
            });
        });
</script>
@endsection
