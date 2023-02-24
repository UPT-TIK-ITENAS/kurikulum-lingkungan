@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">CPL - CPMK / CPMK / </span> Kelola CPMK {{ $datamk[0] }}
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>CPMK : {{ $datamk[0] }} - {{ $datamk[1] }}</b>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#addCPL" class="btn btn-primary float-end"><i
                            class='fas fa-plus mr-2'></i>
                        Tambah CPMK</a>
                </div>
                <div class="card-body">
                    <table id="table-cpmk" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode CPMK</th>
                                <th>Capaian Pembelajaran Mata Kuliah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $no => $c)
                                <tr>
                                    <td width="5%" align="center">{{ $no + 1 }}</td>
                                    <td align="center">{{ $c->kode_cpmk }}</td>
                                    <td>{{ $c->nama_cpmk }}</td>
                                    <td align="center">
                                        <div class='btn-group' role='group' aria-label='Action'>

                                            <a role='button' class='btn btn-icon btn-primary'
                                                href='{{ route('admin.cpmk.cpl', encrypt($c->id)) }}'
                                                data-nama="{{ $c->nama_cpmk }}" data-bs-tooltip='tooltip'
                                                data-bs-offset='0,8' data-bs-placement='top'
                                                data-bs-custom-class='tooltip-success' title='Input relasi dengan CPL'>
                                                CPL
                                            </a>
                                            <a role='button' class='btn btn-icon btn-warning' data-bs-tooltip='tooltip'
                                                data-bs-offset='0,8' data-bs-placement='top'
                                                data-bs-custom-class='tooltip-warning' title='Edit CPL' href="#"
                                                data-bs-toggle="modal" data-bs-target="#editCPMK{{ $c->id }}">
                                                <span class='tf-icons fa-solid fa-edit'></span>
                                            </a>
                                            <a role='button' class='btn btn-icon btn-danger del-btn'
                                                href='{{ route('admin.cpmk.delete', $c->id) }}'
                                                data-nama="{{ $c->nama_cpmk }}" data-bs-tooltip='tooltip'
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
    <div class="modal fade" id="addCPL" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Tambah CPMK Matakuliah {{ $datamk[0] }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.cpmk.store') }}" method="post" class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Kode CPMK</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon11">CPMK-</span>
                                    <input type="number" class="form-control" placeholder="contoh: 1"
                                        aria-label="Kode CPMK" aria-describedby="basic-addon11" id="kode_cpmk"
                                        name="kode_cpmk" required>
                                    <div class="invalid-feedback">Wajib Diisi !</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Nama CPMK</label>
                                <textarea class="form-control" id="nama_cpmk" name="nama_cpmk" required></textarea>
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
        <div class="modal fade" id="editCPMK{{ $c->id }}" tabindex="-1" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Edit CPMK Matakuliah {{ $datamk[0] }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.cpmk.update', $c->id) }}" method="post" class="needs-validation">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Kode CPMK</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">CPMK-</span>
                                        <input type="number" class="form-control" aria-label="Kode CPMK"
                                            aria-describedby="basic-addon11" id="kode_cpmk" name="kode_cpmk"
                                            value="{{ substr($c->kode_cpmk, 5) }}" required>
                                        <div class="invalid-feedback">Wajib Diisi !</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameLarge" class="form-label">Nama CPMK</label>
                                    <textarea class="form-control" id="nama_cpmk" name="nama_cpmk" required>{{ $c->nama_cpmk }}</textarea>
                                    <div class="invalid-feedback">Wajib Diisi !</div>
                                </div>
                            </div>
                            <input type="hidden" name="idmatakuliah" id="idmatakuliah" value="{{ $datamk[0] }}">
                            <input type="hidden" name="nama_matkul" id="nama_matkul" value="{{ $datamk[1] }}">
                            <input type="hidden" name="nama_matkul_en" id="nama_matkul_en"
                                value="{{ $datamk[2] }}">
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
    @endforeach
@endsection
@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-cpmk').DataTable({
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
                // "pageLength": 50
            });
            $('#table-cpmk tbody').on('click', '.del-btn', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Lanjutkan?',
                    text: `Anda akan menghapus CPMK ${$(this).data('nama')}`,
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
