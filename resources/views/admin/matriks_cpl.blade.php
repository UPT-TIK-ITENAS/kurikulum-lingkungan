@extends('layouts.app')
@section('content-header')
    Matriks CPL - CPMK
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="matriks_ce" class="table table-bordered">
                            <thead>
                                <th>No</th>
                                <th>Mata Kuliah</th>
                                <th>SKS</th>
                                @foreach ($data['cpl'] as $cl)
                                    <th>{{ $cl->kode_cpl }}</th>
                                @endforeach
                                <th style="background-color: rgb(228, 228, 228)">Total</th>
                            </thead>
                            <tbody>
                                @foreach ($data['ce'] as $no => $ce)
                                    <tr style="font-size:12px;">
                                        <td>{{ $no + 1 }}</td>
                                        <td>{{ $ce->idmatakuliah . ' - ' . $ce->nama_matkul }}</td>
                                        <td>{{ $ce->sks }}</td>
                                        @foreach ($data['cpl'] as $cpl)
                                            @if($cpl->id)
                                                
                                            <td>{{ getBobotCpl($ce->idmatakuliah, $cpl->id, 'bobot') }}</td>
                                            @endif
                                        @endforeach
                                        <td style="background-color: rgb(228, 228, 228)">{{ getBobotCpl($ce->idmatakuliah, $cpl->id, 'totalbobot') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

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
@endpush
