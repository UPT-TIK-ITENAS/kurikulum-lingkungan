@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">CPL - CPMK - Sub CPMK/ </span> CPL
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>Capaian Pembelajaran Lulusan Program Studi</b>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addCPL" class="btn btn-primary float-end"><i
                            class='fas fa-plus mr-2'></i>
                        Tambah CPL</a>
                </div>
                <div class="card-body">
                    <table id="table-cpl" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode CPL</th>
                                <th>Capaian Pembelajaran Lulusan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cpl as $no => $c)
                                <tr>
                                    <td width="5%" align="center">{{ $no + 1 }}</td>
                                    <td align="center">{{ $c->kode_cpl }}</td>
                                    <td>{{ $c->nama_cpl }} <i> ({{ $c->nama_cpleng }})</i></td>
                                    <td>
                                        <div class='btn-group' role='group' aria-label='Action'>
                                            <a role='button' class='btn btn-icon btn-success' data-bs-tooltip='tooltip'
                                                data-bs-offset='0,8' data-bs-placement='top'
                                                data-bs-custom-class='tooltip-success' title='Input IK'
                                                href="{{ route('admin.cpl.ik', encrypt($c->id)) }}">
                                                IK
                                            </a>
                                            <a role='button' class='btn btn-icon btn-warning' data-bs-tooltip='tooltip'
                                                data-bs-offset='0,8' data-bs-placement='top'
                                                data-bs-custom-class='tooltip-warning' title='Edit CPL' href="#"
                                                data-bs-toggle="modal" data-bs-target="#editCPL{{ $c->id }}">
                                                <span class='tf-icons fa-solid fa-edit'></span>
                                            </a>
                                            <a role='button' class='btn btn-icon btn-danger del-btn'
                                                href='{{ route('admin.cpl.delete', $c->id) }}'
                                                data-nama="{{ $c->nama_cpl }}" data-bs-tooltip='tooltip'
                                                data-bs-offset='0,8' data-bs-placement='top'
                                                data-bs-custom-class='tooltip-danger' title='Hapus CPL'>
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
    <div class="modal fade" id="addCPL" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Tambah CPL Prodi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.cpl.store') }}" method="post" class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Kode CPL</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon11">CPL-</span>
                                    <input type="number" class="form-control" placeholder="contoh: 1" aria-label="Kode CPL"
                                        aria-describedby="basic-addon11" id="kode_cpl" name="kode_cpl" required>
                                    <div class="invalid-feedback">Wajib Diisi !</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Nama CPL</label>
                                <textarea class="form-control" id="nama_cpl" name="nama_cpl" required></textarea>
                                <div class="invalid-feedback">Wajib Diisi !</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Nama CPL (Eng)</label>
                                <textarea class="form-control" id="nama_cpleng" name="nama_cpleng" required></textarea>
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
    @foreach ($cpl as $no => $c)
        <div class="modal fade" id="editCPL{{ $c->id }}" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Edit CPL Prodi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.cpl.update', $c->id) }}" method="post" class="needs-validation">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Kode CPL</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">CPL-</span>
                                        <input type="number" class="form-control" aria-label="Kode CPL"
                                            aria-describedby="basic-addon11" id="kode_cpl" name="kode_cpl"
                                            value="{{ substr($c->kode_cpl, 4) }}" required>
                                        <div class="invalid-feedback">Wajib Diisi !</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Nama CPL</label>
                                    <textarea class="form-control" id="nama_cpl" name="nama_cpl" required>{{ $c->nama_cpl }}</textarea>
                                    <div class="invalid-feedback">Wajib Diisi !</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Nama CPL (Eng)</label>
                                    <textarea class="form-control" id="nama_cpleng" name="nama_cpleng" required>{{ $c->nama_cpleng }}</textarea>
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
                    text: `Anda akan menghapus CPL ${$(this).data('nama')}`,
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
