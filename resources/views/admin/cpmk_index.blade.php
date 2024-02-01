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
                            <td><input type="text" class="form-control" name="semester" id="semester"></td>
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
                <div class="modal-body">
                    <div class="col-md-12 mb-12">
                        <label class="form-label">Dosen</label>
                        <select class="form-control select2" name="dosen" id="dosen">
                            <option value="">--Pilih Dosen--</option>
                            @foreach ($dosen as $d)
                                <option value="{{ $d['nodosMSDOS'] }}"> {{ $d['nodosMSDOS'] }} - {{ $d['dosen'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('scripts')
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script>
        $().ready(function() {
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
                ajax: "{{ route('admin.cpmk.listmatakuliah') }}",
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

            $('#btnCartModal').on('click', function() {
                $('#dosenModal').modal('show')
            })


            // $('#semester').on('input', function() {
            //     // Get the input value
            //     var semesterValue = $(this).val();

            //     // Update the span with the input value (optional)
            //     $('#txtsemester').text(semesterValue);

            //     // Send an AJAX request to your Laravel backend
            //     $.ajax({
            //         type: 'POST',
            //         url: '/update-semester', // Change this to your Laravel route
            //         data: {
            //             semester: semesterValue
            //         },
            //         success: function(response) {
            //             console.log('Data sent successfully:', response);
            //             // You can handle the response here if needed
            //         },
            //         error: function(error) {
            //             console.error('Error sending data:', error);
            //         }
            //     });
            // });
        });
    </script>
@endpush
