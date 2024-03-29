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
                            <th>Persentase</th>
                        </thead>
                        <tbody>
                            @foreach ($data['cpl'] as $no => $c)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>{{ $c->kode_cpl }}</td>
                                    <td>{{ $c->nama_cpl }}</td>
                                    @foreach ($data['total'] as $cpl)
                                        @if ($cpl['idcpl'] == $c->kode_cpl)
                                            <td>{{ round($cpl['total'], 2) }}</td>
                                        @endif
                                    @endforeach
                                    @foreach ($data['total'] as $cpl)
                                        @if ($cpl['idcpl'] == $c->kode_cpl)
                                            <td>{{ round($cpl['total'], 2) }}</td>
                                        @endif
                                    @endforeach
                                    {{-- <td>{{ getNilaiCPL($c->id, $data['mhs'][0]) }}</td> --}}
                                    {{-- <td>{{ round((getNilaiCPL($c->id, $data['mhs'][0]) / 4) * 100) }} %</td> --}}
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
                "pageLength": 25
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
                    var cplChart1 = new Chart("cplChart1", {
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
                                    max: r.max_bobot + 0.5,
                                    grid: {
                                        color: a,
                                        borderColor: a
                                    },
                                    ticks: {
                                        stepSize: 1,
                                        tickColor: a,
                                        color: n
                                    }
                                }
                            }
                        }
                    });
                    var cplChart2 = new Chart("cplChart2", {
                        type: "bar",
                        data: {
                            labels: r.cpl,
                            datasets: [{
                                label: 'Bobot Capaian',
                                data: r.persen,
                                backgroundColor: config.colors.info,
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
                                    max: r.max_persen + 10,
                                    grid: {
                                        color: a,
                                        borderColor: a
                                    },
                                    ticks: {
                                        stepSize: 1,
                                        tickColor: a,
                                        color: n
                                    }
                                }
                            }
                        }
                    });
                    cplChart3 = new Chart("cplChart3", {
                        type: "radar",
                        data: {
                            labels: r.cpl,
                            datasets: [{
                                label: "{{ $data['mhs'][0] . ' - ' . $data['mhs'][1] }}",
                                data: r.bobot,
                                // fill: !0,
                                // pointStyle: "dash",
                                // backgroundColor: "#cfffba",
                                // borderColor: "transparent",
                                // // pointBorderColor: "transparent"
                                fill: true,
                                backgroundColor: 'rgba(242, 145, 0, 0.2)',
                                borderColor: 'rgb(242, 145, 0)',
                                pointBackgroundColor: 'rgb(242, 145, 0)',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: 'rgb(242, 145, 0)'
                            }]
                        },
                        options: {
                            responsive: !0,
                            maintainAspectRatio: !1,
                            animation: {
                                duration: 500
                            },
                            scale: {
                                r: {
                                    angleLines: {
                                        display: true
                                    },
                                    suggestedMin: 0,
                                    suggestedMax: 4
                                }
                            }
                        }
                    })
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON);
                }
            });
        });
    </script>
@endpush
