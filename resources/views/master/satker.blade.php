@extends('layouts.side.side')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header">{{ __('Daftar Satuan Kerja') }}
                    <button class="btn btn-sm btn-primary float-right" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-building-add"></i></button>
                </div>

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
                   @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                    <div class="mb-3">
                        <label for="kantor" class="form-label">Kantor</label>
                        <select name="kantor" id="kantor" class="form-select" required>
                            <option selected disabled value="">Pilih Kantor</option>
                            @foreach($kantor as $office)
                            <option value="{{$office->id}}">{{$office->nama_kantor}}</option>
                            @endforeach
                        </select>
                    </div>
                     @endif
                    <div class="mb-3">
                        <label for="departemen" class="form-label">Departemen</label>
                        <select name="departemen" id="departemen" class="form-select" required>
                            <option selected disabled value="">Pilih Departemen</option>
                            @foreach($departemen as $dept)
                            <option value="{{$dept->id}}">{{$dept->nama_dept}}</option>
                            @endforeach
                        </select>
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
                       
                     @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                    <div class="mb-3">
                        <label for="edit_kantor" class="form-label">Kantor</label>
                        <select name="kantor" id="edit_kantor" class="form-select" required>
                            <option selected value="">Pilih Kantor</option>
                            @foreach($kantor as $office)
                            <option value="{{$office->id}}">{{$office->nama_kantor}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                        <div class="mb-3">
                        <label for="edit_departemen" class="form-label">Departemen</label>
                        {{-- <select name="" id="edit_departemen" class="form-select" disabled>
                            <option selected value="">Pilih Departemen</option>
                            @foreach($departemen as $dept)
                            <option value="{{$dept->id}}">{{$dept->nama_dept}}</option>
                            @endforeach
                        </select> --}}
                        <select name="departemen" id="edit_departemen" class="form-select" required>
                            <option selected value="">Pilih Departemen</option>
                            @foreach($departemen as $dept)
                            <option value="{{$dept->id}}">{{$dept->nama_dept}}</option>
                            @endforeach
                        </select>
                    </div>
                    @else 
                    <div class="mb-3">
                        <label for="edit_departemen" class="form-label">Departemen</label>
                        <select name="departemen" id="edit_departemen" class="form-select" required>
                            <option selected value="">Pilih Departemen</option>
                            @foreach($departemen as $dept)
                            <option value="{{$dept->id}}">{{$dept->nama_dept}}</option>
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

    
        <div class="card-body" style="overflow: auto;"> 
            <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Satuan Kerja</th>
                @if(Auth::user()->role == 0)       
                    <th>Perusahaan</th>
                @endif
                @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                    <th>Kantor</th>
                @endif
                    <th>Departemen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($satker as $key => $ker)
                <tr id="row-{{$ker->id}}">
                    <td class="align-middle text-center">{{$satker->firstitem()+$key}}</td>
                    <td>{{$ker->satuan_kerja}}</td>
                 @if(Auth::user()->role == 0)
                    <td>{{$ker->perusa->perusahaan}}</td>
                @endif 
                @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                    <td>{{$ker->kantor == 0 ? '-' : $ker->kant->nama_kantor}}</td>
                @endif
                    <td>{{$ker->dept_id == 0 ? '-' : $ker->deptmn->nama_dept}}</td>
                    <td class="align-middle text-center">
                        <button class="btn btn-primary btn-sm cen edit-btn" 
                        data-id="{{$ker->id}}" 
                        data-satker="{{$ker->satuan_kerja}}" 
                        data-departemen="{{$ker->dept_id}}"
                    @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                        data-kantor="{{$ker->kantor}}" 
                    @else
                        data-kantor="{{Auth::user()->kantor}}" 
                    @endif
                    @if(Auth::user()->role == 0) 
                        data-perusahaan="{{$ker->perusahaan}}" 
                    @endif>Edit</button>
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
@endsection

@push('script')
<script type="text/javascript">
    // Dynamic load departemen berdasarkan kantor saat tambah
    $('#kantor').change(function () {
        let kantorId = $(this).val();
        if (kantorId) {
            $.get(`/get-sat/${kantorId}`, function (response) {
                let options = '<option value="">Pilih Departemen</option>';
                response.departemen.forEach(dept => {
                    options += `<option value="${dept.id}">${dept.nama_dept}</option>`;
                });
                $('#departemen').html(options);
            });
        } else {
            $('#departemen').html('<option value="">Pilih Departemen</option>');
        }
    });

    // Dynamic load departemen saat modal edit dibuka
    $('#edit_kantor').on('change', function () {
        let kantorId = $(this).val();
        if (kantorId) {
            $.get(`/get-sat/${kantorId}`, function (response) {
                let options = '<option value="">Pilih Departemen</option>';
                response.departemen.forEach(dept => {
                    options += `<option value="${dept.id}" ${dept.id == departemen ? 'selected' : ''}>${dept.nama_dept}</option>`;
                });
                $('#edit_departemen').html(options);


            });
        }
    });

</script>
<script>
$(document).ready(function () {
    // Saat tombol edit diklik
    $(document).on("click", ".edit-btn", function () {
        let btn = $(this);
        let id = btn.data("id");
        let perusahaan = btn.data("perusahaan");
        let kantor = btn.data("kantor");
        let departemen = btn.data("departemen");
        let satker = btn.data("satker");

        $("#edit_id").val(id);
        $("#edit_departemen").html('<option value="">Loading...</option>');
        $("#edit_satker").val(satker);

        @if(Auth::user()->role == 0)
            $("#edit_tenantName").val(perusahaan);
        @endif
        @if(Auth::user()->role == 0 || Auth::user()->role == 1)
            $("#edit_kantor").val(kantor);
        @endif

        $("#editForm").attr("action", "/satker/edit/" + id);
@if(Auth::user()->role == 0)
        if (perusahaan) {
            $.getJSON('/get-konten/' + perusahaan, function (response) {
@else 
        if (kantor) {
            $.getJSON('/get-sat/' + kantor, function (response) {
@endif
                let departemenOptions = '<option value="">Pilih Departemen</option>';

            @if(Auth::user()->role == 0)
                $.each(response.depts, function (i, dept) {
            @else
                $.each(response.departemen, function (i, dept) {
            @endif
                    departemenOptions += `<option value="${dept.id}" ${dept.id == departemen ? 'selected' : ''}>${dept.nama_dept}</option>`;
                });

                $("#edit_departemen").html(departemenOptions);
            });
        }

        $("#editModal").modal("show");
    });

    // Submit form edit dengan konfirmasi
    $("#editForm").on("submit", function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Konfirmasi Perubahan",
            text: "Perubahan ini akan mempengaruhi data terkait. Lanjutkan?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, update!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
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
                                Swal.fire("Terhapus!", data.message, "success");
                                document.getElementById("row-" + id).remove();
                            } else {
                                Swal.fire("Gagal!", data.message, "error");
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

<script>
    $(document).ready(function() {
        $('#kantor').change(function() {
            let perusahaanId = $(this).val();
            if (perusahaanId) {
                $.ajax({
                    url: '/get-sat/' + perusahaanId,
                    type: 'GET',
                    success: function(response) {
                        let departemenOptions = '<option value="">Pilih Departemen</option>';
                        let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';

                        response.departemen.forEach(function(dept) {
                            departemenOptions += `<option value="${dept.id}" ${dept.id == departemen ? 'selected' : ''}>${dept.nama_dept}</option>`;
                        });
                        response.satker.forEach(function(satker) {
                            satkerOptions += `<option value="${satker.id}">${satker.nama_satker}</option>`;
                        });

                        $('#departemen').html(departemenOptions);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                $('#departemen').empty().append('<option value="">Pilih Departemen</option>');
            }
        });
    });
</script>

@endpush