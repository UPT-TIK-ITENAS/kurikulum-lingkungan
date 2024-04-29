@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">Data CPMK - Sub CPMK / </span> Daftar Mata Kuliah
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <table id="tblsemester" width="100%">
                        <tr>
                            <td><b>Program Studi</b></td>
                            <td><b>:</b></td>
                            <td><select name="prodi" id="prodi" class="form-select select2">
                                    <option>-- Pilih Program Studi --</option>
                                    @foreach ($appdata['prodi'] as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Semester</b></td>
                            <td><b>:</b></td>
                            <td><input type="text" class="form-control" name="semester" id="semester"
                                    value="{{ Session::get('semester') }}" maxlength="6" size="6"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="padding-left: 0.3cm"> <b><span id="txtsemester" style="font-size:12px"></span></b>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="card-body">
                    <table id="table_cpmk" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode MK</th>
                                <th>Nama MK (Indonesia)</th>
                                <th>Nama MK (Inggris)</th>
                                <th>Jumlah SKS</th>
                                <th>Wajib / Pilihan</th>
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
            $('#prodi').select2();
            var thsms = document.getElementById("semester");
            $('#semester').inputmask('9999/9');

            var sms = ($('#semester').val()).replace('/', '');
            if (sms.substring(4) == '1') {
                $('#txtsemester').text(' SEMESTER GANJIL ' + sms.substring(0, 4) + '/' + (
                    parseInt(
                        sms
                        .substring(0, 4)) +
                    1));
            } else if (sms.substring(4) == '2') {
                $('#txtsemester').text(' SEMESTER GENAP ' + sms.substring(0, 4) + '/' + (
                    parseInt(
                        sms
                        .substring(
                            0, 4)) +
                    1));
            } else {
                $('#txtsemester').text(' SEMESTER PENDEK ' + sms.substring(0, 4) + '/' + (
                    parseInt(
                        sms
                        .substring(0, 4)) +
                    1));
            }

            let table = $('#table_cpmk').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                columnDefs: [{
                    responsivePriority: 1,
                    targets: 1
                }, ],
                ajax: {
                    url: "{{ route('fakultas.cpmk.listmatakuliah') }}",
                    data: function(d) {
                        d.semester = ($('#semester').val()).replace('/', '') ? ($('#semester').val())
                            .replace('/', '') : '<>',
                            d.prodi = $('#prodi').val();

                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'kode_mk',
                        name: 'kode_mk',
                        className: 'text-center'
                    },
                    {
                        data: 'nama_mk',
                        name: 'nama_mk',
                    },
                    {
                        data: 'nama_mk_en',
                        name: 'nama_mk_en',
                    },
                    {
                        data: 'sks',
                        name: 'sks',
                        className: 'text-center'
                    },
                    {
                        data: 'status_mk',
                        name: 'status_mk',
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
            $("#semester").on('change', function() {
                table.draw();
            });
            $("#prodi").on('change', function() {
                table.draw();
            });

            thsms.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                    var sms = ($('#semester').val()).replace('/', '');
                    if (sms.substring(4) == '1') {
                        $('#txtsemester').text(' SEMESTER GANJIL ' + sms.substring(0, 4) + '/' + (
                            parseInt(
                                sms
                                .substring(0, 4)) +
                            1));
                    } else if (sms.substring(4) == '2') {
                        $('#txtsemester').text(' SEMESTER GENAP ' + sms.substring(0, 4) + '/' + (
                            parseInt(
                                sms
                                .substring(
                                    0, 4)) +
                            1));
                    } else {
                        $('#txtsemester').text(' SEMESTER PENDEK ' + sms.substring(0, 4) + '/' + (
                            parseInt(
                                sms
                                .substring(0, 4)) +
                            1));
                    }
                }
            });
        });
    </script>
@endpush
