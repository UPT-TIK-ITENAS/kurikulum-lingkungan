@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">CPL - CPMK - Sub CPMK / </span> Bobot
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
                                <th></th>
                                @foreach ($data['subcpmk'] as $sub)
                                    <th>{{ $sub->subcpmk_kode }}</th>
                                @endforeach
                                <th>Total</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($data['cpmk'] as $cpmk)
                                <tr>
                                    <td>{{ $cpmk->kode_cpmk }}</td>
                                    @foreach ($data['subcpmk'] as $idx => $sub)
                                        <td>
                                            <div class="input_bobot" data-id="{{ $cpmk->id }}_{{ $sub->subcpmk_id }}">
                                                <input type="number" name="bobot" class="form-control bot"
                                                    style="text-align: center"
                                                    id="bobot_{{ $cpmk->id }}_{{ $sub->subcpmk_id }}"
                                                    data-mk="{{ $cpmk->idmatakuliah }}" data-cpmk="{{ $cpmk->kode_cpmk }}"
                                                    data-sub="{{ $sub->subcpmk_kode }}" class="form-control base"
                                                    value="{{ $bobot[$cpmk->id][$sub->subcpmk_id] }}">
                                            </div>
                                        </td>
                                    @endforeach
                                    <td><input type="text" class="form-control total" style="text-align: center"
                                            id="total_{{ $cpmk->id }}" value="{{ totalCPMK($cpmk->id) }}" readonly>
                                    </td>
                                    {{-- @foreach ($data['sumbobot'] as $summ)
                                        <td><input type="text" class="form-control total" style="text-align: center"
                                                data-id="{{ $cpmk->id }}"
                                                value="{{ $cpmk->id == $summ->id_cpmk ? $summ->totalbobot : '' }}">
                                        </td>
                                    @endforeach --}}

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
            $(document).on("change", ".bot", function(e) {
                let row = $(this).closest('tr');
                let total = 0;
                row.find('.bot').each(function() {
                    total += parseInt($(this).val());
                });
                row.find('.total').val(total);
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
                var url = "{{ route('admin.bobot.store') }}";
                var data = [];
                $('.input_bobot').each(function(i, v) {
                    var id = $(this).data('id');
                    // console.log(id);
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
