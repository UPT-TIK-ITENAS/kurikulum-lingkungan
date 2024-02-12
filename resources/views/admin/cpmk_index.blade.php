@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">Data CPMK - Sub CPMK / </span> Capaian Pembelajaran Mata Kuliah (Daftar Mata Kuliah)
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <table id="tblsemester">
                        <tr>
                            <td><b>Semester : </b></td>
                            <td><input type="text" class="form-control" name="semester" id="semester"
                                    value="{{ Session::get('semester') }}"></td>
                            <td style="padding-left: 0.3cm"> <b><span id="txtsemester"></span></b></td>
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

@push('modal')
    <div class="modal fade" id="dosenModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Koordinator Dosen Pengampu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form autocomplete="off" class="needs-validation" novalidate=""
                    action="{{ route('admin.cpmk.store_pengampu') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Kode Mata Kuliah</label>
                                <input type="text" class="form-control" name="kdmk" id="kdmk" readonly>
                            </div>
                            <div class="col-md-8 mb-8">
                                <label class="form-label">Nama Mata Kuliah</label>
                                <input type="text" class="form-control" name="nmmk" id="nmmk" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-6">
                                <label class="form-label">Nama Mata Kuliah (en)</label>
                                <input type="text" class="form-control" name="nakmi" id="nakmi" readonly>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">SKS</label>
                                <input type="text" class="form-control" name="sks" id="sks" readonly>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">Status</label>
                                <input type="hidden" name="wpil" id="wpil">
                                <input type="text" class="form-control" name="wpil2" id="wpil2" readonly>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">Semester</label>
                                <input type="text" class="form-control" name="semester1" id="semester1" readonly>
                            </div>
                        </div>

                        <div class="col-md-12 mb-12">
                            <label class="form-label">Dosen</label>
                            <select class="form-control select2" name="dosen" id="dosen">
                                <option value="" selected>--Pilih Dosen--</option>
                                @foreach ($dosen as $d)
                                    <option value="{{ $d['nodosMSDOS'] }} | {{ $d['dosen'] }}">
                                        {{ $d['nodosMSDOS'] }} -
                                        {{ $d['dosen'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
@push('scripts')
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script>
        $().ready(function() {

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

            $('#dosen').select2({
                dropdownParent: $("#dosenModal")
            });
            let table = $('#table_cpmk').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                columnDefs: [{
                    responsivePriority: 1,
                    targets: 1
                }, ],
                ajax: {
                    url: "{{ route('admin.cpmk.listmatakuliah') }}",
                    data: function(d) {
                        d.semester = ($('#semester').val()).replace('/', '') ? ($('#semester').val())
                            .replace('/', '') : '<>';
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

            var thsms = document.getElementById("semester");
            $('#semester').inputmask('9999/9');
            thsms.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                    var sms = ($('#semester').val()).replace('/', '');
                    if (sms.substring(4) == '1') {
                        $('#txtsemester').text(' SEMESTER GANJIL ' + sms.substring(0, 4) + '/' + (parseInt(
                                sms
                                .substring(0, 4)) +
                            1));
                    } else if (sms.substring(4) == '2') {
                        $('#txtsemester').text(' SEMESTER GENAP ' + sms.substring(0, 4) + '/' + (parseInt(
                                sms
                                .substring(
                                    0, 4)) +
                            1));
                    } else {
                        $('#txtsemester').text(' SEMESTER PENDEK ' + sms.substring(0, 4) + '/' + (parseInt(
                                sms
                                .substring(0, 4)) +
                            1));
                    }
                }
            });

            $('#dosenModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var kdmk = button.data('kdmk');
                var nmmk = button.data('nmmk');
                var nakmi = button.data('nakmi');
                var sks = button.data('sks');
                var wpil = button.data('wpil');
                var dosen = button.data('dosen');
                var nmdosen = button.data('nmdosen');
                // Update modal content with data
                $('#kdmk').val(kdmk);
                $('#nmmk').val(nmmk);
                $('#nakmi').val(nakmi);
                $('#sks').val(sks);
                $('#wpil').val(wpil);
                $('#semester1').val(sms);

                if (dosen != null && nmdosen != null) {
                    $('#dosen').val(dosen + '|' + nmdosen).trigger('change');
                } else {
                    $("#dosen").val("").trigger("change");
                }
                console.log($('#dosen').val());

                if (wpil == 'W') {
                    $('#wpil2').val('Wajib');
                } else {
                    $('#wpil2').val('Pilihan');
                }


            });
        });
    </script>
@endpush
