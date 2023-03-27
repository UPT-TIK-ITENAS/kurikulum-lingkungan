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

                    <table id="table-bobot" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th style="font-size: 70%">Mata Kuliah</th>
                                @foreach ($cpl_mk as $c)
                                    <th>{{ $c->kode_cpl }}</th>
                                @endforeach
                                <th>Total</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($dataMatkul as $data)
                                <tr>
                                    <td style="font-size: 80%">{{ $data['kdkmktbkmk'] }} | {{ trim($data['nakmktbkmk']) }}
                                    </td>
                                    @foreach ($cpl_mk as $c)
                                        <td style="font-size: 80%;">
                                            {{ !empty($bobot) ? $bobot[$data['kdkmktbkmk']][$c->kode_cpl] : '0' }}
                                        </td>
                                    @endforeach
                                    </td>
                                    <td><input type="text" class="form-control total"
                                            style="text-align: center;font-size: 70%;" name="bobot_mk" id="bobot_mk"
                                            value="{{ totalBobotPerMK($data['kdkmktbkmk']) }}" readonly>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        {{-- <tfoot>
                            <tr align="center">
                                <th colspan="1">Total</th>
                                @foreach ($cpl_mk as $c)
                                    <th>
                                        <input type="text" name="bobot_cpl" id="bobot_cpl" class="form-control"
                                            style="text-align: center;font-size: 90%;"
                                            value="{{ totalBobotPerMK($c->kode_cpl) }}" readonly>
                                    </th>
                                @endforeach
                            </tr>

                        </tfoot> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script></script>
@endpush
