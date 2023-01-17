@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">CPL - CPMK / CPMK / Kelola CPMK {{ $data['cpmk']->idmatakuliah }} / </span> Kelola
    SubCPMK
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-secondary"><b>{{ $data['cpmk']->kode_cpmk }}</b> {{ $data['cpmk']->nama_cpmk }}</div>
            <div class="card">
                <div class="card-header">
                    <b>Sub Capaian Pembelajaran Mata Kuliah</b>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addSubCPMK" class="btn btn-primary float-end"><i
                            class='fas fa-plus mr-2'></i>
                        Tambah SubCPMK</a>
                </div>
                <div class="card-body">
                    <table id="table-cpl" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode SubCPMK</th>
                                <th>Sub CPMK</th>
                                <th>Bobot</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['subcpmk'] as $no => $c)
                                <tr>
                                    <td width="5%" align="center">{{ $no + 1 }}</td>
                                    <td align="center" width="15%">{{ $c->subcpmk_kode }}</td>
                                    <td>{{ $c->subcpmk_nama_id }} <i> ({{ $c->subcpmk_nama_en }})</i></td>
                                    <td align="center" width="5%">{{ $c->subcpmk_bobot }}</td>
                                    <td align="center" width="15%">
                                        <div class='btn-group' role='group' aria-label='Action'>
                                            <a role='button' class='btn btn-icon btn-warning' data-bs-tooltip='tooltip'
                                                data-bs-offset='0,8' data-bs-placement='top'
                                                data-bs-custom-class='tooltip-warning' title='Edit SubCPMK' href="#"
                                                data-bs-toggle="modal" data-bs-target="#editSubCPMK{{ $c->subcpmk_id }}">
                                                <span class='tf-icons fa-solid fa-edit'></span>
                                            </a>
                                            <a role='button' class='btn btn-icon btn-danger del-btn'
                                                href='{{ route('admin.cpmk.subcpmk.delete', $c->subcpmk_id) }}'
                                                data-nama="{{ $c->subcpmk_nama_id }}" data-bs-tooltip='tooltip'
                                                data-bs-offset='0,8' data-bs-placement='top'
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
                <form action="{{ route('admin.cpmk.subcpmk.store') }}" method="post" class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="datacpmk" id="datacpmk" value="{{ encrypt($data['cpmk']) }}">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Kode Sub CPMK</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon11">SubCPMK-</span>
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
                                <label for="nameLarge" class="form-label">Nama IK (Eng)</label>
                                <textarea class="form-control" id="subcpmk_nama_en" name="subcpmk_nama_en" required></textarea>
                                <div class="invalid-feedback">Wajib Diisi !</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Bobot SubCPMK terhadap CPMK</label>
                                <input type="text" class="form-control angka" id="subcpmk_bobot" name="subcpmk_bobot"
                                    required></input>
                                <div class="invalid-feedback">Wajib Diisi !</div>
                            </div>
                        </div>
                        <small>*) angka koma dipisahkan dengan titik</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach ($data['subcpmk'] as $no => $c)
        <div class="modal fade" id="editSubCPMK{{ $c->subcpmk_id }}" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Edit Indikator Kinerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.cpmk.subcpmk.update', $c->subcpmk_id) }}" method="post"
                        class="needs-validation">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Kode Sub CPMK</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">SubCPMK-</span>
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
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Bobot SubCPMK terhadap CPMK</label>
                                    <input type="text" class="form-control angka" id="subcpmk_bobot"
                                        name="subcpmk_bobot" required value="{{ $c->subcpmk_bobot }}">
                                    <div class="invalid-feedback">Wajib Diisi !</div>
                                </div>
                            </div>
                            <small>*) angka koma dipisahkan dengan titik</small>
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
                    text: `Anda akan menghapus SubCPMK - ${$(this).data('nama')}`,
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
