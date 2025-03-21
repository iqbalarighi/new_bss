@extends('layouts.side.side')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header">{{ __('Daftar Tenant') }}
                    <button class="btn btn-sm btn-primary float-right" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-building-add"></i></button>
                </div>

                <div class="card-body">
@if (Session::get('status'))
<script>
        Swal.fire({
          title: "Berhasil",
          icon: "success",
          text: "{{Session::get('status')}}",
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
         thead {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
     <!-- Modal Tambah -->                    
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered animate__animated animate__zoomIn">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Daftar Tenant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/tenant/tambah" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="tenantName" class="form-label">Nama Tenant</label>
                            <input type="text" class="form-control" id="tenantName" name="tenant" placeholder="Masukkan nama tenant" required>
                        </div>
                        <div class="mb-3">
                            <label for="tenantAddress" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="tenantAddress" name="alamat" placeholder="Masukkan alamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="tenantPhone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="tenantPhone" name="telp" placeholder="Masukkan nomor telepon" required onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div> 
            </form>
            </div>
        </div>
    </div>
    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Tenant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editTenantId" name="id">
                        <div class="mb-3">
                            <label for="editTenantName" class="form-label">Nama Tenant</label>
                            <input type="text" class="form-control" id="editTenantName" name="tenant" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTenantAddress" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="editTenantAddress" name="alamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTenantPhone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="editTenantPhone" name="telp" required onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div> 
            </form>
            </div>
        </div>
    </div>
    
    <div style="overflow: auto;"> 
        <table class="table table-striped table-bordered table-hover" >
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Tenant</th>
                    <th>Alamat</th>
                    <th>No. Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($perusahaan as $key => $usaha)
                <tr id="row-{{ $usaha->id }}">
                    <td class="align-middle text-center">{{$perusahaan->firstitem()+$key}}</td>
                    <td>{{$usaha->perusahaan}}</td>
                    <td>{{$usaha->alamat}}</td>
                    <td>{{$usaha->no_tlp}}</td>
                    <td class="align-middle text-center">
                        <button class="btn btn-primary btn-sm edit-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal"
                                data-id="{{$usaha->id}}"
                                data-name="{{$usaha->perusahaan}}"
                                data-address="{{$usaha->alamat}}"
                                data-phone="{{$usaha->no_tlp}}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{$usaha->id}}">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $perusahaan->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>

<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('editTenantId').value = this.dataset.id;
            document.getElementById('editTenantName').value = this.dataset.name;
            document.getElementById('editTenantAddress').value = this.dataset.address;
            document.getElementById('editTenantPhone').value = this.dataset.phone;
            document.getElementById('editForm').action = `/tenant/edit/${this.dataset.id}`;
        });
    });
</script>
<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const tenantId = this.dataset.id;
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data tenant ini akan dihapus dan seluruh tabel terkait tenant akan dihapus!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: "Masukkan password untuk menghapus data! ",
                        input: 'password',
                        inputAttributes: {
                            autocapitalize: 'off',
                            placeholder: 'Masukkan password'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: (password) => {
                            return fetch(`/tenant/hapus/${tenantId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ password: password })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(err => { throw new Error(err.message) });
                                }
                                return response.json();
                            })
                            .catch(error => {
                                Swal.showValidationMessage(`Error: ${error.message}`);
                            });
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire('Berhasil!', 'Data telah dihapus.', 'success');
                            document.getElementById(`row-${tenantId}`).remove();
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
