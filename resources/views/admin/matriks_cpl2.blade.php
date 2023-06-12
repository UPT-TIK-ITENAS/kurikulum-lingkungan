@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">CPL / </span> Matriks bobot Setiap CPL
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>Bobot CPL Padu setiap Mata Kuliah</b>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-bobot" class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th style="font-size: 70%">Mata Kuliah</th>
                                    @foreach ($cpl as $c)
                                        <th>{{ $c->kode_cpl }}</th>
                                    @endforeach
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($dataMatkul as $data)
                                    <tr>
                                        @if ($data['wbpiltbkur'] == 'P')
                                            <td style="font-size: 80%">
                                                {{ trim($data['nakmktbkmk']) }}
                                            </td>
                                        @else
                                            <td style="font-size: 80%">{{ $data['kdkmktbkmk'] }} |
                                                {{ trim($data['nakmktbkmk']) }}
                                            </td>
                                        @endif
                                        @foreach ($cpl as $c)
                                            <td style="font-size: 80%;">
                                                {{ !empty($bobot) ? number_format($bobot[$data['kdkmktbkmk']][$c->kode_cpl] ?? '0', 2) : '0' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr align="center">
                                    <th colspan="1">Total</th>
                                    @foreach ($cpl as $c)
                                        <th>
                                            <input type="text" name="bobot_cpl" id="bobot_cpl" class="form-control"
                                                style="text-align: center;font-size: 80%;"
                                                value="{{ round(totalBobotPerCPL($c->kode_cpl)) }}" readonly>
                                        </th>
                                    @endforeach
                                </tr>

                            </tfoot>
                        </table>
                    </div>
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
            $('#table-bobot').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
            });
        })
    </script>
@endpush
