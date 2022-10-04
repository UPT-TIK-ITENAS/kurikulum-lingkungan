@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">Data Master / Mahasiswa / </span> CPL Mahasiswa
    {{ $data['mhs'][0] }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>CPL Mahasiswa {{ $data['mhs'][0] . ' - ' . $data['mhs'][1] }}</b>
                </div>
                <div class="card-body">
                    <table id="table-cpl-mhs" class="table table-bordered">
                        <thead>
                            <th>No</th>
                            <th>Kode CPL</th>
                            <th>Nama CPL</th>
                            <th>Nilai</th>
                        </thead>
                        <tbody>
                            @foreach ($data['cpl'] as $no => $c)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $c->kode_cpl }}</td>
                                    <td>{{ $c->nama_cpl }}</td>
                                    <td>{{ getNilaiCPL($c->id, $data['mhs'][0]) }}</td>
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
            $('#table-cpl-mhs').DataTable({
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
            });
        });
    </script>
@endpush
