@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">Data Master / </span> Mahasiswa
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>Daftar Mahasiswa</b>
                </div>
                <div class="card-body">
                    <table id="table_mahasiswa" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>IPS</th>
                                <th>IPK</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
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
            let table = $('#table_mahasiswa').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                columnDefs: [{
                    responsivePriority: 1,
                    targets: 1
                }, ],
                ajax: "{{ route('admin.mahasiswa.listmahasiswa') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'NIMHSHSIPK',
                        name: 'NIMHSHSIPK',
                        className: 'text-center'
                    },
                    {
                        data: 'nmmhsMSMHS',
                        name: 'nmmhsMSMHS',
                    },
                    {
                        data: 'NLIPSHSIPK',
                        name: 'NLIPSHSIPK',
                        className: 'text-center'
                    },
                    {
                        data: 'NLIPKHSIPK',
                        name: 'NLIPKHSIPK',
                        className: 'text-center'
                    },
                    {
                        data: 'KeterTbkod',
                        name: 'KeterTbkod',
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
        });
    </script>
@endpush
