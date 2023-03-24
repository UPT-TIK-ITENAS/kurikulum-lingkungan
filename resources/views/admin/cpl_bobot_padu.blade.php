@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">CPL - CPMK - Sub CPMK/ </span> CPL Bobot
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>Bobot CPL Padu Yang Dibebankan Pada Mata Kuliah</b>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col">
                            <a class='btn btn-icon btn-primary ' style='float: right;padding: 15px 45px;' href='#'
                                id="saveBtn">
                                <i class="fa fa-save me-1"></i> Simpan
                            </a>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </div>
                    <table id="table-bobot" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th style="font-size: 70%">Mata Kuliah</th>
                                @foreach ($cpl as $c)
                                    <th>{{ $c->kode_cpl }}</th>
                                @endforeach
                                <th style="font-size: 70%">Total</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($dataMatkul as $data)
                                <tr class="a">
                                    <td style="font-size: 80%">{{ $data['kdkmktbkmk'] }} | {{ trim($data['nakmktbkmk']) }}
                                    </td>
                                    @foreach ($cpl as $c)
                                        <td>
                                            <div class="input_bobot"
                                                data-id="{{ $data['kdkmktbkmk'] }}_{{ $c->kode_cpl }}">
                                                <input type="text" name="bobot" class="form-control bot"
                                                    style="text-align: center;font-size: 70%;"
                                                    id="bobot_{{ $data['kdkmktbkmk'] }}_{{ $c->kode_cpl }}"
                                                    data-mk="{{ $data['kdkmktbkmk'] }}" data-cpl="{{ $c->kode_cpl }}"
                                                    class="form-control base"
                                                    value="{{ !empty($bobot) ? $bobot[$data['kdkmktbkmk']][$c->kode_cpl] : '0' }} ">
                                            </div>
                                        </td>
                                    @endforeach
                                    <td><input type="text" class="form-control total"
                                            style="text-align: center;font-size: 70%;" name="bobot_mk" id="bobot_mk"
                                            value="{{ totalBobotCPLPaduPerMK($data['kdkmktbkmk'], 'cpl_padu') }}" readonly>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr align="center">
                                <th colspan="1">Total</th>
                                @foreach ($cpl as $c)
                                    <th>
                                        <input type="text" name="bobot_cpl" id="bobot_cpl" class="form-control totalCPL"
                                            style="text-align: center;font-size: 90%;"
                                            value="{{ totalBobotCPLPaduPerCPL($c->kode_cpl) }}" readonly>
                                    </th>
                                @endforeach
                                <th>
                                    <input type="text" name="bobot" class="form-control grandTotal"
                                        style="text-align: center;font-size: 90%;"
                                        value="{{ !empty($total_nilai) ? $total_nilai['totalbobot'] : '0' }}" readonly>
                                </th>
                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script>
        $(document).ready(function() {
            const table = document.getElementById("table-bobot");
            $(document).on("change", ".bot", function(e) {
                let row = $(this).closest('tr');

                let total = 0;
                row.find('.bot ').each(function() {
                    total += Number($(this).val());
                });
                row.find('.total').val(total);

                const totalElements = document.getElementsByClassName('total');
                let grandTotal = 0;
                totalElements.forEach(function(item) {
                    grandTotal += Number(item.value);
                })
                $('.grandTotal').val(grandTotal);

                subTotal(table);

            });

            const subTotal = (table) => {
                const rows = table.rows;
                const numCols = rows[0].cells.length - 1;
                const totals = new Array(numCols).fill(0);
                for (let i = 1; i < rows.length; i++) {
                    for (let j = 1; j < numCols; j++) {
                        // totals[j] += parseFloat(rows[i].cells[j]);
                        let element = rows[i].cells[j];

                        // check if element is td
                        if (element.tagName === "TD") {
                            // get the input element
                            let input = element.querySelector('input[name="bobot"]');
                            totals[j] += parseFloat(input.value);
                        }
                    }
                }

                const total = totals.slice(1);

                // mapping total to tfoot input
                const tfoot = document.getElementsByTagName("tfoot")[0];
                const inputs = tfoot.querySelectorAll(".totalCPL");
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].value = total[i];
                }
            };

            subTotal(table);

            // $('#table-bobot').DataTable({
            //     "paging": true,
            //     "lengthChange": true,
            //     "searching": true,
            //     "ordering": true,
            //     "info": true,
            //     "autoWidth": false,
            //     "responsive": true,
            //     "lengthMenu": [
            //         [10, 25, 50, -1],
            //         [10, 25, 50, "All"]
            //     ],
            //     "pageLength": 25,
            // });

            var saveButton = document.getElementById("saveBtn");
            saveButton.addEventListener("click", function(event) {
                event.preventDefault();
                var url = "{{ route('admin.cpl.storeBobotCPLPadu') }}";
                var data = [];
                $('.input_bobot').each(function(i, v) {
                    var id = $(this).data('id');
                    var bobot = $('#bobot_' + id).val() == undefined ? 0 : $('#bobot_' + id).val();
                    var cpl = $('#bobot_' + id).data('cpl');
                    var idmatakuliah = $('#bobot_' + id).data('mk');
                    var bobot_mk = $('#bobot_mk').val();
                    var bobot_cpl = $('#bobot_cpl').val();

                    console.log(bobot_cpl);
                    data.push({
                        'bobot': bobot,
                        'id_cpl': cpl,
                        'idmatakuliah': idmatakuliah,
                        'bobot_mk': bobot_mk,
                        'bobot_cpl': bobot_cpl,
                    });
                    // console.log(data);
                });
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "data": data,
                    },
                    success: function(data) {
                        // console.log(data);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Indikator berhasil diubah',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 1700
                        })
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Gagal',
                            text: 'Terjadi Kesalahan',
                            type: 'warning',
                            showConfirmButton: false,
                            timer: 1700
                        })
                    }
                });
            });




        });
    </script>
@endpush
