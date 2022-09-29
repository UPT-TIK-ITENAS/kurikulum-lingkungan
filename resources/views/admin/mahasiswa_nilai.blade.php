@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">Data Master / <a href="{{ route('admin.mahasiswa.index') }}">Mahasiswa</a> / </span>
    Nilai
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>Daftar Nilai {{ $datamhs[0] . ' - ' . $datamhs[1] }}</b>
                </div>
                <div class="card-body">
                    <table id="table-nilai" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode MK</th>
                                <th>Nama MK</th>
                                <th>Semester</th>
                                <th>SKS</th>
                                <th>Nilai (Angka)</th>
                                <th>Nilai (Huruf)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $no => $d)
                                <tr>
                                    <td width="5%" align="center">{{ $no + 1 }}</td>
                                    <td align="center">{{ $d['KDKMKHSNIL'] }}</td>
                                    <td>{{ $d['nakmktbkmk'] }}</td>
                                    <td>{{ $d['THSMSHSNIL'] }}</td>
                                    <td>{{ $d['SKSMKHSNIL'] }}</td>
                                    <td>{{ $d['BOBOTHSNIL'] }}</td>
                                    <td>{{ $d['NLAKHHSNIL'] }}</td>
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
            $('#table-nilai').DataTable({
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
                // "pageLength": 50
            });
        });
    </script>
@endpush
