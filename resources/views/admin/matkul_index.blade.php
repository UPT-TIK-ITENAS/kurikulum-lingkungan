@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">Data Master / </span> Matakuliah
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>Daftar Mata Kuliah</b>
                </div>
                <div class="card-body">
                    <table id="table_matkul" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode MK</th>
                                <th>Nama MK (Indonesia)</th>
                                <th>Nama MK (Inggris)</th>
                                <th>Jumlah SKS</th>
                                <th>Wajib / Pilihan</th>
                                {{-- <th>Capaian CPL Terhadap Matakuliah</th>
                                <th>Capaian CPL Terhadap Mahasiswa</th> --}}
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editcplMK" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Edit CPL terhadap Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.matkul.storemk') }}" method="post" id="data"
                    enctype="multipart/form-data" class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Kode Mata Kuliah</label>
                                <input type="text" class="form-control" aria-label="Kode CPL"
                                    aria-describedby="basic-addon11" id="kdmk" name="kdmk" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Nama Mata Kuliah</label>
                                <input type="text" class="form-control" aria-label="Kode CPL"
                                    aria-describedby="basic-addon11" id="nmmk" name="nmmk" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">CPl terhadap Mata Kuliah</label>
                                <input type="file" class="form-control" aria-label="Kode CPL"
                                    aria-describedby="basic-addon11" id="file" name="file" required>
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

    <div class="modal fade" id="showcplMK" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Show CPL terhadap Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="data" class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="kdmk" name="kdmk">
                        <input type="hidden" id="nmmk" name="nmmk">
                        <img id="gambarmk" name="gambarmk" width="100%">
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editcplMhs" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Edit CPL terhadap Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.matkul.storemhs') }}" method="post" id="datamhs"
                    enctype="multipart/form-data" class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Kode Mata Kuliah</label>
                                <input type="text" class="form-control" aria-label="Kode CPL"
                                    aria-describedby="basic-addon11" id="kdmkk" name="kdmkk" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">Nama Mata Kuliah</label>
                                <input type="text" class="form-control" aria-label="Kode CPL"
                                    aria-describedby="basic-addon11" id="nmmkk" name="nmmkk" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameLarge" class="form-label">CPl terhadap Mahasiswa</label>
                                <input type="file" class="form-control" aria-label="Kode CPL"
                                    aria-describedby="basic-addon11" id="file" name="file"
                                    data-allowed-file-extensions="jpg png jpeg svg" required>
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

    <div class="modal fade" id="showcplMhs" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Show CPL terhadap Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="datamhs" class="needs-validation">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="kdmkk" name="kdmkk">
                        <input type="hidden" id="nmmkk" name="nmmkk">
                        <img id="gambarmhs" name="gambarmhs" width="100%">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script>
        $().ready(function() {
            let table = $('#table_matkul').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                columnDefs: [{
                    responsivePriority: 1,
                    targets: 1
                }, ],
                ajax: "{{ route('admin.matkul.listmatakuliah') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'kdkmktbkmk',
                        name: 'kdkmktbkmk',
                        className: 'text-center'
                    },
                    {
                        data: 'nakmktbkmk',
                        name: 'nakmktbkmk',
                    },
                    {
                        data: 'nakmitbkmk',
                        name: 'nakmitbkmk',
                    },
                    {
                        data: 'sksmktbkmk',
                        name: 'sksmktbkmk',
                        className: 'text-center'
                    },
                    {
                        data: 'wbpiltbkur',
                        name: 'wbpiltbkur',
                        className: 'text-center'
                    },
                    // {
                    //     data: 'cpl_mk',
                    //     name: 'cpl_mk',
                    //     className: 'text-center'
                    // },
                    // {
                    //     data: 'cpl_mhs',
                    //     name: 'cpl_mhs',
                    //     className: 'text-center'
                    // },
                ],
            });
            $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
                console.log(message);
            };

            const myDropzone = new Dropzone('#data', {
                previewTemplate: previewTemplate,
                parallelUploads: 1,
                maxFilesize: 5,
                addRemoveLinks: true,
                maxFiles: 1
            });
        });


        function showedit(elem) {
            var kdmk = $(elem).data('kdmk');
            var nmmk = $(elem).data('nmmk');
            var gambarmk = $(elem).data('gambarmk');
            $("#data #kdmk").val(kdmk);
            $("#data #nmmk").val(nmmk);
            $("#data #gambarmk").attr("src", gambarmk);
        }

        function show(elem) {
            var kdmkk = $(elem).data('kdmkk');
            var nmmkk = $(elem).data('nmmkk');
            var gambarmhs = $(elem).data('gambarmhs');
            $("#datamhs #kdmkk").val(kdmkk);
            $("#datamhs #nmmkk").val(nmmkk);
            $("#datamhs #gambarmhs").attr("src", gambarmhs);
        }
    </script>
@endpush
