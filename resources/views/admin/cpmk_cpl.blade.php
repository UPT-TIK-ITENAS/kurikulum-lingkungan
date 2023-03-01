@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">CPL - CPMK - Sub CPMK/ CPMK / </span> Relasi CPL - CPMK
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>{{ $data['cpmk']->idmatakuliah . ' : ' . $data['cpmk']->kode_cpmk }} </b>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addCPMKCPL" class="btn btn-primary float-end"><i
                            class='fas fa-plus mr-2'></i>
                        Tambah Relasi</a>
                </div>
                <div class="card-body">
                    <table id="table_cpmk_cpl" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode CPL</th>
                                <th>Bobot CPMK terhadap CPL</th>
                                <th>Bobot CPMK terhadap MK</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['ce'] as $no => $d)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $d->kode_cpl }} : {{ $d->nama_cpl }} </td>
                                    <td>{{ $d->bobot_cpl }}</td>
                                    <td>{{ $d->bobot_cpmk }}</td>
                                    <td>
                                        <div class='btn-group' role='group' aria-label='Action'>
                                            <a role='button' class='btn btn-icon btn-warning' data-bs-tooltip='tooltip'
                                                data-bs-offset='0,8' data-bs-placement='top'
                                                data-bs-custom-class='tooltip-warning' title='Edit CPL' href="#"
                                                data-bs-toggle="modal" data-bs-target="#editCPMKCPL{{ $d->idce }}">
                                                <span class='tf-icons fa-solid fa-edit'></span>
                                            </a>
                                            <a role='button' class='btn btn-icon btn-danger del-btn'
                                                href='{{ route('admin.cpmk.delete_cpmk_cpl', $d->idce) }}'
                                                data-nama="{{ $d->kode_cpl }}" data-bs-tooltip='tooltip'
                                                data-bs-offset='0,8' data-bs-placement='top'
                                                data-bs-custom-class='tooltip-danger' title='Hapus CPMK'>
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
    <div class="modal fade" id="addCPMKCPL" style="display: none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Tambah Relasi CPMK - CPL</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.cpmk.store_cpmk_cpl') }}" method="post" class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Kode CPL</label>
                                <select class="form-control select2cpl" id="select2cpl" name="idcpl">
                                    <option selected value="">Pilih CPL</option>
                                    @foreach ($data['cpl'] as $cp)
                                        <option value="{{ $cp->id }}">{{ $cp->kode_cpl . ' ' . $cp->nama_cpl }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label for="nameLarge" class="form-label">Bobot CPMK terhadap CPL</label>
                                <input type="text" class="form-control" id="bobot_cpl" name="bobot_cpl" required></input>
                                <div class="invalid-feedback">Wajib Diisi !</div>
                            </div>
                            <div class="col-6">
                                <label for="nameLarge" class="form-label">Bobot CPMK terhadap MK</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="bobot_cpmk" name="bobot_cpmk" required>
                                    <span class="input-group-text" id="basic-addon13">%</span>
                                </div>
                                <div class="invalid-feedback">Wajib Diisi !</div>
                            </div>
                        </div>
                        <small>*) angka koma dengan titik</small>
                        <input type="hidden" name="idcpmk" id="idcpmk" value="{{ $data['cpmk']->id }}">
                        <input type="hidden" name="idmatakuliah" id="idmatakuliah"
                            value="{{ $data['cpmk']->idmatakuliah }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach ($data['ce'] as $ce)
        <div class="modal fade" id="editCPMKCPL{{ $ce->idce }}" style="display: none;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Edit Relasi CPMK - CPL</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.cpmk.update_cpmk_cpl', $ce->idce) }}" method="post"
                        class="needs-validation">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Kode CPL</label>
                                    <select class="form-control select2cpl" name="idcpl"
                                        id="select2cpl{{ $ce->idce }}">
                                        @foreach ($data['cpl'] as $cp)
                                            <option @if ($cp->id == $ce->cpl_id) selected @endif
                                                value="{{ $cp->id }}">
                                                {{ $cp->kode_cpl . ' ' . $cp->nama_cpl }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label for="nameLarge" class="form-label">Bobot CPMK terhadap CPL</label>
                                    <input type="text" class="form-control" id="bobot_cpl" name="bobot_cpl" required
                                        value="{{ $ce->bobot_cpl }}"></input>
                                    <div class="invalid-feedback">Wajib Diisi !</div>
                                </div>
                                <div class="col-6">
                                    <label for="nameLarge" class="form-label">Bobot CPMK terhadap MK</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="bobot_cpmk" name="bobot_cpmk"
                                            required value="{{ $ce->bobot_cpmk }}">
                                        <span class="input-group-text" id="basic-addon13">%</span>
                                    </div>
                                    <div class="invalid-feedback">Wajib Diisi !</div>
                                </div>
                            </div>
                            <small>*) angka koma dengan titik</small>
                            <input type="hidden" name="idcpmk" id="idcpmk" value="{{ $data['cpmk']->id }}">
                            <input type="hidden" name="idmatakuliah" id="idmatakuliah"
                                value="{{ $data['cpmk']->idmatakuliah }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @push('scripts')
            <script>
                $(document).ready(function() {
                    $("#select2cpl{{ $ce->idce }}").select2({
                        dropdownParent: $("#editCPMKCPL{{ $ce->idce }}")
                    });
                });
            </script>
        @endpush
    @endforeach
@endsection


@push('scripts')
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table_cpmk_cpl').DataTable({
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
            $('#table_cpmk_cpl tbody').on('click', '.del-btn', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Lanjutkan?',
                    text: `Anda akan menghapus ${$(this).data('nama')}`,
                    icon: 'question',
                    showConfirmButton: true,
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = $(this).attr('href');
                    }
                });
            });
            $(document).ready(function() {
                $("#select2cpl").select2({
                    dropdownParent: $("#addCPMKCPL")
                });

                $('input').on('change, keyup', function() {
                    var currentInput = $(this).val();
                    var fixedInput = currentInput.replace(/[A-Za-z!@#$%^&*()]/g, '');
                    $(this).val(fixedInput);
                });
            });
        });
    </script>
@endpush
