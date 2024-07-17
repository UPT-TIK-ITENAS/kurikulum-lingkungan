@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">CPL - CPMK - Sub CPMK / </span> Bobot CPMK - Sub CPMK {{ $datamk[0] }}
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>Mata Kuliah : {{ $datamk[0] }} - {{ $datamk[1] }}</b>
                </div>
                <div class="card-body">
                    <b>Memiliki CPL :</b>
                    <table cellpadding="10" class="table table-bordered">
                        <thead>
                            <tr style="background-color: rgb(228, 228, 228)">
                                <th class="text-center">No</th>
                                <th class="text-center">Capaian Pembelajaran Lulusan</th>
                                <th class="text-center">Bobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cpl_mk as $no => $c)
                                <tr>
                                    <td class="text-center">{{ $no + 1 }}</td>
                                    <td>{{ $c->id_cpl }} | {{ $c->nama_cpl }}</td>
                                    <td>{{ round($c->bobot_mk) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    {{-- <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading mb-1">Perhatian !</h6>
                        <p align="justify"> Jika sudah di <b>SIMPAN</b> secara langsung tersimpan ke <b>Sistem Akademik
                                (SIKAD)</b>. Hati-hati dalam pengisian bobot.
                            <br>
                            Batas akhir pengisian bobot semester <b>
                                {{ substr(Session::get('semester'), 0, 4) . '/' . substr(Session::get('semester'), 4) }}</b>
                            pada tanggal <b>
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', Session::get('akhir_semester'))->translatedFormat('d F Y') }}</b>.
                        </p>
                    </div> --}}
                    <div class="row mb-2">
                        <div class="col" id="divSavebtn" style="display: block;">
                            @if (Session::get('awal_semester') <= date('Y-m-d') || date('Y-m-d') <= Session::get('akhir_semester'))
                                <a class='btn btn-icon btn-primary ' style='float: right;padding: 15px 45px;' href='#'
                                    id="saveBtn">
                                    <i class="fa fa-save me-1"></i> Simpan
                                </a>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            @endif
                        </div>
                    </div>
                    <table id="table-bobot" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th></th>
                                @foreach ($data['subcpmk'] as $sub)
                                    <th>{{ $sub->subcpmk_kode }}</th>
                                @endforeach
                                <th>Total</th>
                            </tr>

                        </thead>
                        <tbody>
                            <div class="alert alert-danger" id="lebih" style="display: none;">
                                ⚠️ Total bobot tidak sesuai dengan ketentuan! Periksa kembali total bobot
                            </div>
                            @foreach ($data['cpmk'] as $cpmk)
                                <tr>
                                    <td align="center">{{ $cpmk->kode_cpmk }}</td>
                                    @foreach ($data['subcpmk'] as $idx => $sub)
                                        <td>
                                            <div class="input_bobot" data-id="{{ $cpmk->id }}_{{ $sub->subcpmk_id }}">
                                                <input type="number" name="bobot" class="form-control bot"
                                                    style="text-align: center"
                                                    id="bobot_{{ $cpmk->id }}_{{ $sub->subcpmk_id }}"
                                                    data-mk="{{ $cpmk->idmatakuliah }}" data-cpmk="{{ $cpmk->kode_cpmk }}"
                                                    data-sub="{{ $sub->subcpmk_kode }}" class="form-control base"
                                                    value="{{ isset($bobot[$cpmk->id][$sub->subcpmk_id]) ? $bobot[$cpmk->id][$sub->subcpmk_id] : '0' }}">
                                            </div>
                                        </td>
                                    @endforeach
                                    <td><input type="text" class="form-control total" style="text-align: center" readonly
                                            id="total_{{ $cpmk->id }}"
                                            value="{{ totalCPMKDosen($cpmk->id, $datamk[4]) }}" readonly>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr align="center">
                                <th>Total</th>
                                @foreach ($data['subcpmk'] as $sub)
                                    <th>
                                        <div class="bobotsc">
                                            <input type="number" name="sc_bobot" class="form-control totalsc" readonly
                                                style="text-align: center" data-id="{{ $sub->subcpmk_id }}"
                                                data-kode="{{ $sub->subcpmk_kode }}"
                                                value="{{ totalSCDosen($sub->subcpmk_id, $datamk[4]) }}">
                                        </div>
                                    </th>
                                @endforeach
                                <th>
                                    <input type="text" name="bobot" class="form-control grandTotal"
                                        style="text-align: center;"
                                        value="{{ !empty($bobotsubcpmk) ? $bobotsubcpmk->totalbobot : '0' }} " readonly>
                                </th>
                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>Matriks CPMK - SubCPMK : {{ $datamk[0] }} - {{ $datamk[1] }}</b>
                </div>
                <div class="card-body">
                    <table id="table-bobot" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th></th>
                                @foreach ($data['subcpmk'] as $sub)
                                    <th>{{ $sub->subcpmk_kode }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['cpmk'] as $cpmk)
                                <tr>
                                    <td>{{ $cpmk->kode_cpmk }}</td>
                                    @foreach ($data['subcpmk'] as $idx => $sub)
                                        <td align="center">
                                            <div>
                                                @if (!empty($bobot) && isset($bobot[$cpmk->id][$sub->subcpmk_id]))
                                                    {{ $bobot[$cpmk->id][$sub->subcpmk_id] != 0 ? '✔' : '' }}
                                                @endif
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
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
            $(document).on("keyup", ".bot", function(e) {
                const table = document.getElementById("table-bobot");

                let row = $(this).closest('tr');
                let total = 0;
                row.find('.bot').each(function() {
                    total += parseFloat($(this).val());
                });
                row.find('.total').val(total);

                const TotalSCnya = (table) => {
                    const rows = table.rows;
                    const numCols = rows[0].cells.length - 1;
                    const totals = new Array(numCols).fill(0);

                    for (let i = 1; i < rows.length; i++) {
                        for (let j = 1; j < numCols; j++) {
                            let element = rows[i].cells[j];

                            if (element && element.tagName === "TD") {
                                // get the input element
                                let input = element.querySelector('input[name="bobot"]');
                                if (input) {
                                    totals[j] += parseFloat(input.value);
                                }
                            }
                        }
                    }

                    const totalsc = totals.slice(1);

                    // mapping total to tfoot input
                    const tfoot = document.getElementsByTagName("tfoot")[0];
                    const inputs = tfoot.querySelectorAll(".totalsc");

                    for (let i = 0; i < inputs.length; i++) {
                        inputs[i].value = totalsc[i];
                    }
                };

                const totalElements = document.getElementsByClassName('total');
                let grandTotal = 0;
                totalElements.forEach(function(item) {
                    grandTotal += Number(item.value);
                });
                $('.grandTotal').val(grandTotal);
                if (grandTotal > 100) {
                    $('#lebih').css('display', 'block');
                    $('#divSavebtn').css('display', 'none');
                } else if (grandTotal < 100) {
                    $('#lebih').css('display', 'block');
                    $('#divSavebtn').css('display', 'none');
                } else {
                    $('#lebih').css('display', 'none');
                    $('#divSavebtn').css('display', 'block');
                }
                TotalSCnya(table);
            });
            $('#table-bobot').DataTable({
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
                "pageLength": 25,
            });

            var saveButton = document.getElementById("saveBtn");
            saveButton.addEventListener("click", function(event) {
                event.preventDefault();
                var url = "{{ route('dosen.bobot.store') }}";
                var data = [];
                var datasc = [];
                $('.input_bobot').each(function(i, v) {
                    var id = $(this).data('id');
                    var bobot = $('#bobot_' + id).val() == undefined ? 0 : $('#bobot_' + id).val();
                    var cpmk = $('#bobot_' + id).data('cpmk');
                    var subcpmk = $('#bobot_' + id).data('sub');
                    var idmatakuliah = $('#bobot_' + id).data('mk');
                    var split = id.split("_");

                    data.push({
                        'bobot': bobot,
                        'kode_cpmk': cpmk,
                        'subcpmk_kode': subcpmk,
                        'idmatakuliah': idmatakuliah,
                        'id_cpmk': split[0],
                        'id_subcpmk': split[1],
                        'semester': '{{ $datamk[4] }}'
                    });
                });

                $('.totalsc').each(function(i, v) {
                    var id = $(this).data('id');
                    var kode = $(this).data('kode');
                    var bobotsc = $(this).val();

                    datasc.push({
                        'id': id,
                        'kode': kode,
                        'bobotsc': bobotsc,
                        'semester': '{{ $datamk[4] }}'
                    });

                });
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "data": data,
                        "datasc": datasc
                    },
                    success: function(data) {
                        // console.log(data);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Bobot berhasil diubah',
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
