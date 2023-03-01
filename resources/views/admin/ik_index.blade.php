@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">CPL - CPMK - Sub CPMK/ CPL /</span> IK
    <br>
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-secondary"><b>{{ $data['cpl']->kode_cpl }}</b> {{ $data['cpl']->nama_cpl }}
                (<i>{{ $data['cpl']->nama_cpleng }}</i>)</div>
            <div class="card">
                <div class="card-header">
                    <b>Indikator Kinerja</b>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addIK" class="btn btn-primary float-end"><i
                            class='fas fa-plus mr-2'></i>
                        Tambah IK</a>
                </div>
                <div class="card-body">
                    <table id="table-cpl" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode IK</th>
                                <th>Indikator Kinerja</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['ik'] as $no => $c)
                                <tr>
                                    <td width="5%" align="center">{{ $no + 1 }}</td>
                                    <td align="center" width="10%">{{ $c->ik_kode }}</td>
                                    <td>{{ $c->ik_nama_id }} <i> ({{ $c->ik_nama_en }})</i></td>
                                    <td align="center" width="10%">
                                        <div class='btn-group' role='group' aria-label='Action'>
                                            <a role='button' class='btn btn-icon btn-warning' data-bs-tooltip='tooltip'
                                                data-bs-offset='0,8' data-bs-placement='top'
                                                data-bs-custom-class='tooltip-warning' title='Edit IK' href="#"
                                                data-bs-toggle="modal" data-bs-target="#editIK{{ $c->ik_id }}">
                                                <span class='tf-icons fa-solid fa-edit'></span>
                                            </a>
                                            <a role='button' class='btn btn-icon btn-danger del-btn'
                                                href='{{ route('admin.cpl.ik.delete', $c->ik_id) }}'
                                                data-nama="{{ $c->ik_nama_id }}" data-bs-tooltip='tooltip'
                                                data-bs-offset='0,8' data-bs-placement='top'
                                                data-bs-custom-class='tooltip-danger' title='Hapus IK'>
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
    <div class="modal fade" id="addIK" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Tambah Indikator Kinerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.cpl.ik.store') }}" method="post" class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="datacpl" id="datacpl" value="{{ encrypt($data['cpl']->id) }}">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Kode IK</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon11">IK-</span>
                                    <input type="number" class="form-control" placeholder="contoh: 1" aria-label="Kode IK"
                                        aria-describedby="basic-addon11" id="ik_kode" name="ik_kode" required>
                                    <div class="invalid-feedback">Wajib Diisi !</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Nama IK</label>
                                <textarea class="form-control" id="ik_nama_id" name="ik_nama_id" required></textarea>
                                <div class="invalid-feedback">Wajib Diisi !</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Nama IK (Eng)</label>
                                <textarea class="form-control" id="ik_nama_en" name="ik_nama_en" required></textarea>
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
    @foreach ($data['ik'] as $no => $c)
        <div class="modal fade" id="editIK{{ $c->ik_id }}" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Edit Indikator Kinerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.cpl.ik.update', $c->ik_id) }}" method="post"
                        class="needs-validation">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Kode IK</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">IK-</span>
                                        <input type="number" class="form-control" placeholder="contoh: 1"
                                            aria-label="Kode IK" aria-describedby="basic-addon11" id="ik_kode"
                                            name="ik_kode" value="{{ substr($c->ik_kode, 3) }}" required>
                                        <div class="invalid-feedback">Wajib Diisi !</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Nama IK</label>
                                    <textarea class="form-control" id="ik_nama_id" name="ik_nama_id" required>{{ $c->ik_nama_id }}</textarea>
                                    <div class="invalid-feedback">Wajib Diisi !</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Nama IK (Eng)</label>
                                    <textarea class="form-control" id="ik_nama_en" name="ik_nama_en" required>{{ $c->ik_nama_en }}</textarea>
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
                    text: `Anda akan menghapus IK ${$(this).data('nama')}`,
                    icon: 'question',
                    showConfirmButton: true,
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = $(this).attr('href');
                    }
                });
            });
        });
    </script>
@endpush
