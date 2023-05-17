@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">Data Master / Mahasiswa / </span> Sub CPMK Mahasiswa
    {{ $data['mhs'][0] }}
@endsection

@section('content')
    {{-- <div class="row mb-4">
        <div class="col-12">
            <div class="col-xl-12 col-12">
                <div class="card">
                    <div class="card-header header-elements">
                        <h5 class="card-title mb-0">Grafik Capaian Mahasiswa terhadap CPL Prodi <br>
                            {{ $data['mhs'][0] . ' - ' . $data['mhs'][1] }}
                        </h5>
                        <div class="card-action-element ms-auto py-0">

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <canvas id="cplChart1" class="chartjs" data-height="400" height="400"
                                    width="243"></canvas>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6">
                                <canvas id="cplChart2" class="chartjs" data-height="400" height="400"
                                    width="243"></canvas>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <canvas id="cplChart3" class="chartjs" data-height="400" height="400"
                                    width="243"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>List Mata Kuliah </b>
                </div>
                <div class="card-body">
                    <table id="table-cpl-mhs" class="table table-bordered">
                        <thead>
                            <th>No</th>
                            <th>Kode Mata Kuliah</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @foreach ($data['datamhs'] as $no => $c)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $c['kdkmkMSAKM'] }}</td>
                                    <td>{{ $c['nakmktbkmk'] }}</td>
                                    <td> <a href="#" class="btn btn-success show" data-bs-target="#showSC"
                                            data-bs-toggle="modal" data-id="{{ $c['kdkmkMSAKM'] }}">Lihat</a></td>
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
    <div class="modal fade bd-example-modal-lg" id="showSC" aria-labelledby="myLargeModalLabel" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Data Sub CPMK</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"
                        data-bs-original-title="" title=""></button>
                </div>
                <form autocomplete="off" class="needs-validation" novalidate="" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <table id="table-cpl-mhs" class="table table-bordered">
                            <thead>
                                <th>No</th>
                                <th>Kode Mata Kuliah</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                {{-- @foreach ($data['datasubcpmk'] as $no => $c)
                                    <tr>
                                        <td>{{ $no + 1 }}</td>
                                        <td>{{ $c['kdkmkMSAKM'] }}</td>
                                        <td>{{ $c['nakmktbkmk'] }}</td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>

                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script src="{{ asset('vendor/libs/chartjs/chartjs.js') }}"></script>
    <script>
        $('body').on('click', '.show', function() {
            var id = $(this).data('id');
            console.log(`${window.baseurl}/admin/data-master/mahasiswa/subCPMK/${id}`);
            $.get(`${window.baseurl}/admin/data-master/mahasiswa/subCPMK/${id}`, function(data) {
                $('#ModalTitle').html('Shift');
                $('#edit-shift').modal('show');
                $('#id').val(data.id);
                $('#id').val(data.);
                console.log(data);
            })
        });
    </script>
@endpush
