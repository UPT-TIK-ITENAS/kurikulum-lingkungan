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
                                <th style="font-size: 70%">Total</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($dataMatkul as $data)
                                <tr>
                                    <td style="font-size: 80%">{{ $data['kdkmktbkmk'] }} | {{ trim($data['nakmktbkmk']) }}
                                    </td>
                                    @foreach ($cpl_mk as $c)
                                        <td>{{ !empty($bobot) ? $bobot[$data['kdkmktbkmk']][$c->kode_cpl] : '0' }}
                                        </td>
                                    @endforeach
                                    <td>{{ totalBobotCPLPaduPerMK($data['kdkmktbkmk'], 'mk') }}
                                    </td>
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
    <script></script>
@endpush
