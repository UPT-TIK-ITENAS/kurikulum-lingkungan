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
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
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
                ],
            });
            $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
                console.log(message);
            };
        });
    </script>
@endpush