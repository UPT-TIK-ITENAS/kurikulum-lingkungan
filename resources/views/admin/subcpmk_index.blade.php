@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">Data CPMK - Sub CPMK / Sub CPMK / </span> Kelola Sub CPMK {{ $datamk[0] }}
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>Sub CPMK : {{ $datamk[0] }} - {{ $datamk[1] }}</b>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addSubCPMK" class="btn btn-primary float-end"><i
                            class='fas fa-plus mr-2'></i>
                        Tambah SubCPMK</a>
                </div>
                <div class="card-body">
                    <b>Memiliki CPL :</b>
                    <table cellpadding="10" class="table table-bordered">
                        <thead>
                            <tr style="background-color: rgb(228, 228, 228)">
                                <th class="text-center">No</th>
                                <th class="text-center">Capaian Pembelajaran Lulusan</th>
                                <th class="text-center">Bobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cpl_mk as $no => $c)
                                <tr>
                                    <td class="text-center">{{ $no + 1 }}</td>
                                    <td>{{ $c->id_cpl }} | {{ $c->cpl->nama_cpl }}</td>
                                    <td>{{ round($c->bobot_mk) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table id="table-cpl" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode Sub CPMK</th>
                                <th>Sub Capaian Pembelajaran Mata Kuliah (Id)</th>
                                <th>Sub Capaian Pembelajaran Mata Kuliah (En)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $no => $c)
                                <tr>
                                    <td width="5%" align="center">{{ $no + 1 }}</td>
                                    <td align="center">{{ $c->subcpmk_kode }}</td>
                                    <td>{{ $c->subcpmk_nama_id }}</td>
                                    <td>{{ $c->subcpmk_nama_en }}</td>
                                    <td align="center">
                                        <div class='btn-group' role='group' aria-label='Action'>
                                            <a role='button' class='btn btn-icon btn-warning' data-bs-tooltip='tooltip'
                                                data-bs-offset='0,8' data-bs-placement='top'
                                                data-bs-custom-class='tooltip-warning' title='Edit SubCPMK' href="#"
                                                data-bs-toggle="modal" data-bs-target="#editSubCPMK{{ $c->subcpmk_id }}">
                                                <span class='tf-icons fa-solid fa-edit'></span>
                                            </a>
                                            <a role='button' class='btn btn-icon btn-danger del-btn'
                                                href='{{ route('admin.subcpmk.delete', $c->subcpmk_id) }}'
                                                data-nama="{{ $c->subcpmk_nama_id }}" data-id="{{ $c->subcpmk_kode }}"
                                                data-bs-tooltip='tooltip' data-bs-offset='0,8' data-bs-placement='top'
                                                data-bs-custom-class='tooltip-danger' title='Hapus SubCPMK'>
                                                <span class='tf-icons fa-solid fa-trash'></span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addSubCPMK" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Tambah Sub CPMK</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.subcpmk.store') }}" method="post" class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Kode Sub CPMK</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon11">SC-</span>
                                    <input type="number" class="form-control" placeholder="contoh: 1"
                                        aria-label="Kode Sub CPMK" aria-describedby="basic-addon11" id="subcpmk_kode"
                                        name="subcpmk_kode" required>
                                    <div class="invalid-feedback">Wajib Diisi !</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Nama Sub CPMK</label>
                                <textarea class="form-control" id="subcpmk_nama_id" name="subcpmk_nama_id" required></textarea>
                                <div class="invalid-feedback">Wajib Diisi !</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Nama SubCPMK (Eng)</label>
                                <textarea class="form-control" id="subcpmk_nama_en" name="subcpmk_nama_en" required></textarea>
                                <div class="invalid-feedback">Wajib Diisi !</div>
                            </div>
                        </div>
                        <input type="hidden" name="idmatakuliah" id="idmatakuliah" value="{{ $datamk[0] }}">
                        <input type="hidden" name="nama_matkul" id="nama_matkul" value="{{ $datamk[1] }}">
                        <input type="hidden" name="nama_matkul_en" id="nama_matkul_en" value="{{ $datamk[2] }}">
                        <input type="hidden" name="sks" id="sks" value="{{ $datamk[3] }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach ($data as $no => $c)
        <div class="modal fade" id="editSubCPMK{{ $c->subcpmk_id }}" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Edit Indikator Kinerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.subcpmk.update', $c->subcpmk_id) }}" method="post"
                        class="needs-validation">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Kode Sub CPMK</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">SC-</span>
                                        <input type="number" class="form-control" placeholder="contoh: 1"
                                            aria-label="Kode IK" aria-describedby="basic-addon11" id="subcpmk_kode"
                                            name="subcpmk_kode" value="{{ substr($c->subcpmk_kode, 8) }}" required>
                                        <div class="invalid-feedback">Wajib Diisi !</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Nama Sub CPMK</label>
                                    <textarea class="form-control" id="subcpmk_nama_id" name="subcpmk_nama_id" required>{{ $c->subcpmk_nama_id }}</textarea>
                                    <div class="invalid-feedback">Wajib Diisi !</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Nama Sub CPMK (Eng)</label>
                                    <textarea class="form-control" id="subcpmk_nama_en" name="subcpmk_nama_en" required>{{ $c->subcpmk_nama_en }}</textarea>
                                    <div class="invalid-feedback">Wajib Diisi !</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-cpl').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "pageLength": 25
            });
            $('#table-cpl tbody').on('click', '.del-btn', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Lanjutkan?',
                    text: `Anda akan menghapus ${$(this).data('id')}`,
                    icon: 'question',
                    showConfirmButton: true,
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = $(this).attr('href');
                    }
                });
            });
            $('.angka').on('change, keyup', function() {
                var currentInput = $(this).val();
                var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
                $(this).val(fixedInput);
            });
        });
    </script>
@endpush
