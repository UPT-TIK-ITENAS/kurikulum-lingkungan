@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">Pengakategorian Mata Kuliah Pilihan / </span> Daftar Mata Kuliah
@endsection
@section('content')
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b> Pengakategorian Mata Kuliah Pilihan</b>
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
                    <table id="table-mkp" class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>Kode MK</th>
                                <th>Nama MK</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $no => $ce)
                                <tr>
                                    <td>{{ $ce['kdkmktbkmk'] }}</td>
                                    <td>{{ $ce['nakmktbkmk'] }}</td>
                                    <td>
                                        <div class="matakuliah" data-id="{{ $ce['kdkmktbkmk'] }}">
                                            <select class="form-select" name="kategori"
                                                id="kategori_{{ $ce['kdkmktbkmk'] }}">
                                                <option value="" disabled selected>Pilih Kategori</option>
                                                @foreach ($mkpilihan as $mk)
                                                    <option value="{{ $mk['kdkmktbkmk'] }}"
                                                        @if ($pilihan[$ce['kdkmktbkmk']] == $mk['kdkmktbkmk']) selected @endif required>
                                                        {{ $mk['nakmktbkmk'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
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
    <script>
        $(document).ready(function() {
            $('#table-mkp').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 25
            });

            var saveButton = document.getElementById("saveBtn");
            saveButton.addEventListener("click", function(event) {
                event.preventDefault();
                var url = "{{ route('admin.mkp.store') }}";
                var data = [];
                $('.matakuliah').each(function(i, v) {
                    var mk = $(this).data('id');
                    var kategori = $('#kategori_' + mk).val();
                    data.push({
                        'kategori': kategori,
                        'kode_mk': mk,
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
