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
                                <th width="10%">CPMK</th>
                                @foreach ($data['cpl'] as $cl)
                                    <th>{{ $cl->kode_cpl }}</th>
                                @endforeach
                            </thead>
                            <tbody>
                                @foreach ($data['ce'] as $no => $ce)
                                    <tr style="font-size:12px;">
                                        <td>{{ $no + 1 }}</td>
                                        <td>{{ $ce->idmatakuliah . ' - ' . $ce->nama_matkul }}</td>
                                        <td>{{ $ce->sks }}</td>
                                        <td>{{ $ce->kode_cpmk }}</td>
                                        @foreach ($data['cpl'] as $cpl)
                                            <td>{{ getBobot($ce->idce, $ce->cpmk_id, $cpl->id,'bobot') }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr align="center">
                                    <th colspan="4">Total</th>
                                        @foreach ($data['cpl'] as $cpl)
                                            <td>{{ round(getBobot(null, null, $cpl->id,'totalbobot'),2) }}</td>
                                        @endforeach
                                    </td>
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
@endpush
