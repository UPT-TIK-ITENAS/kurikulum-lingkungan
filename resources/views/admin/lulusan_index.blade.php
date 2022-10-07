@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">Data Master / </span> Lulusan
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>Daftar Lulusan</b>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addLulusan" class="btn btn-primary float-end"><i
                            class='fas fa-plus mr-2'></i>
                        Tambah Lulusan</a>
                </div>
                <div class="card-body">
                    <table id="table-lulusan" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Semester Lulus</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addLulusan" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Tambah Lulusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.lulusan.store') }}" method="post" class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">NIM</label>
                                <select class="select2" name="nim" id="nim">
                                    <option value="" disabled selected> -- Pilih Mahasiswa --</option>
                                    @foreach ($data['mhs'] as $m)
                                        <option value="{{ $m['NIMHSHSIPK'] . '|' . $m['nmmhsMSMHS'] }}">
                                            {{ $m['NIMHSHSIPK'] . ' - ' . $m['nmmhsMSMHS'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Semester Lulus</label>
                                <input type="text" class="form-control" id="semester_lulus" name="semester_lulus"
                                    required placeholder="contoh : 2021/1"></input>
                            </div>
                        </div>
                        <small>*) 1 : Ganjil, 2 : Genap</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
    <script>
        $().ready(function() {
            let table = $('#table-lulusan').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                columnDefs: [{
                    responsivePriority: 1,
                    targets: 1
                }, ],
                ajax: "{{ route('admin.lulusan.listlulusan') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'nim',
                        name: 'nim',
                        className: 'text-center'
                    },
                    {
                        data: 'nama_mhs',
                        name: 'nama_mhs',
                    },
                    {
                        data: 'semester_lulus',
                        name: 'semester_lulus',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center'
                    },
                ],
            });
            $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
                console.log(message);
            };
            $('#table-lulusan tbody').on('click', '.del-btn', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Lanjutkan?',
                    text: `Anda akan menghapus Lulusan ${$(this).data('nama')}`,
                    icon: 'question',
                    showConfirmButton: true,
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = $(this).attr('href');
                    }
                });
            });

            $(".select2").select2({
                dropdownParent: $("#addLulusan")
            });
            $('#semester_lulus').inputmask('9999/9');
        });
    </script>
@endpush
