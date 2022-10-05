@extends('layouts.app')
@section('content-header')
    <span class="text-muted fw-light">Data Master / Mahasiswa / </span> CPL Mahasiswa
    {{ $data['mhs'][0] }}
@endsection

@section('content')
    <div class="row mb-4">
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
                        <canvas id="cplChart" class="chartjs" data-height="400" height="400" width="457"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <b>Tabel Capaian Mahasiswa terhadap CPL Prodi</b>
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
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script src="{{ asset('vendor/libs/chartjs/chartjs.js') }}"></script>
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

            var o = "#836AF9",
                r = "#ffe800",
                t = "#28dac6",
                l = "#EDF1F4",
                i = "#2B9AFF",
                e = "#84D0FF";
            let a, n;

            n = isDarkStyle ? (a = config.colors_dark.borderColor, config.colors_dark.axisColor) : (a = config
                .colors.borderColor, config.colors.axisColor);
            const d = document.querySelectorAll(".chartjs");
            d.forEach(function(o) {
                o.height = o.dataset.height
            });

            $.ajax({
                type: "GET",
                url: "{{ route('admin.cpl.getLabelCPLChart', $data['en_mhs']) }}",
                success: function(r) {
                    var cplChart = new Chart("cplChart", {
                        type: "bar",
                        data: {
                            labels: r.cpl,
                            datasets: [{

                                label: 'Bobot Capaian',
                                data: r.bobot,
                                backgroundColor: t,
                                borderColor: "transparent",
                                maxBarThickness: 15,
                                borderRadius: {
                                    topRight: 15,
                                    topLeft: 15
                                }
                            }]
                        },
                        options: {
                            responsive: !0,
                            maintainAspectRatio: !1,
                            animation: {
                                duration: 500
                            },
                            plugins: {
                                tooltip: {
                                    rtl: isRtl,
                                    backgroundColor: config.colors.white,
                                    titleColor: config.colors.black,
                                    bodyColor: config.colors.black,
                                    borderWidth: 1,
                                    borderColor: a
                                },
                                legend: {
                                    display: !1
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        color: a,
                                        borderColor: a
                                    },
                                    ticks: {
                                        color: n
                                    }
                                },
                                y: {
                                    min: 0,
                                    max: 10,
                                    grid: {
                                        color: a,
                                        borderColor: a
                                    },
                                    ticks: {
                                        stepSize: 100,
                                        tickColor: a,
                                        color: n
                                    }
                                }
                            }
                        }
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON);
                }
            });


        });
    </script>
@endpush
