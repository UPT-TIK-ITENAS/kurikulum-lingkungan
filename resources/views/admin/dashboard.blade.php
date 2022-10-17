@extends('layouts.app')
@section('content-header')
    Dashboards
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="col-xl-12 col-12">
                <div class="card">
                    <div class="card-header header-elements">
                        <h5 class="card-title mb-0">Grafik Capaian Lulusan terhadap CPL Prodi <br>
                        </h5>
                        <div class="card-action-element ms-auto py-0">
                            <select id="select2semester">
                                <option value="" disabled selected>Pilih Semester</option>
                                @foreach ($data['tahunAkademik'] as $t)
                                    @php
                                        $tahunnya = substr($t->semester_lulus, 0, 4);
                                        $next_tahun = (int) $tahunnya + 1;
                                    @endphp
                                    <option value="{{ $t->semester_lulus }}">
                                        @if (substr($t->semester_lulus, 4, 1) == 1)
                                            {{ 'Semester Ganjil ' . substr($t->semester_lulus, 0, 4) . '/' . $next_tahun }}
                                        @elseif(substr($t->semester_lulus, 4, 1) == 2)
                                            {{ 'Semester Genap ' . substr($t->semester_lulus, 0, 4) . '/' . $next_tahun }}
                                        @elseif(substr($t->semester_lulus, 4, 1) == 3)
                                            {{ 'Semester Pendek ' . substr($t->semester_lulus, 0, 4) . '/' . $next_tahun }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <canvas id="cplChart" class="chartjs" data-height="400" height="400"
                                    width="228"></canvas>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <canvas id="cplChart2" class="chartjs" data-height="400" height="400"
                                    width="228"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="col-xl-12 col-12">
                <div class="card">
                    <div class="card-header header-elements">
                        <h5 class="card-title mb-0">Grafik Capaian Mahasiswa terhadap CPL Prodi <br>
                        </h5>
                        <div class="card-action-element ms-auto py-0">
                            <select id="select2semesterMhs">
                                <option value="" disabled selected>Pilih Semester</option>
                                @foreach ($data['tahunAkademik'] as $t)
                                    @php
                                        $tahunnya = substr($t->semester_lulus, 0, 4);
                                        $next_tahun = (int) $tahunnya + 1;
                                    @endphp
                                    <option value="{{ $t->semester_lulus }}">
                                        @if (substr($t->semester_lulus, 4, 1) == 1)
                                            {{ 'Semester Ganjil ' . substr($t->semester_lulus, 0, 4) . '/' . $next_tahun }}
                                        @elseif(substr($t->semester_lulus, 4, 1) == 2)
                                            {{ 'Semester Genap ' . substr($t->semester_lulus, 0, 4) . '/' . $next_tahun }}
                                        @elseif(substr($t->semester_lulus, 4, 1) == 3)
                                            {{ 'Semester Pendek ' . substr($t->semester_lulus, 0, 4) . '/' . $next_tahun }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <canvas id="cplChartMhs" class="chartjs" data-height="400" height="400"
                                    width="228"></canvas>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <canvas id="cplChartMhs2" class="chartjs" data-height="400" height="400"
                                    width="228"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script src="{{ asset('vendor/libs/chartjs/chartjs.js') }}"></script>
    <script>
        $(document).ready(function() {
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

            var cplChart = new Chart("cplChart", {
                type: "bar",
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Bobot Capaian',
                        data: [],
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
                            max: 4,
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

            cplChart2 = new Chart("cplChart2", {
                type: "radar",
                data: {
                    labels: [],
                    datasets: [{
                        label: "Lulusan pada semester yang dipilih",
                        data: [],
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
                    }
                }
            });
            var cplChartMhs = new Chart("cplChartMhs", {
                type: "bar",
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Bobot Capaian',
                        data: [],
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
                            max: 4,
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

            cplChartMhs2 = new Chart("cplChartMhs2", {
                type: "radar",
                data: {
                    labels: [],
                    datasets: [{
                        label: "Mahasiswa pada semester yang dipilih",
                        data: [],
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
                    }
                }
            });

            $('#select2semester').select2().on("change", function(e) {
                console.log($(this).val())
                let semester = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.cpl.getLabelCPLChartBySemester') }}",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'semester': semester
                    },
                    success: function(r) {
                        console.log(r);
                        cplChart.data.datasets[0].data = r.bobot;
                        cplChart.data.labels = r.cpl;
                        cplChart.options.scales.y.max = r.max_bobot + 0.5;
                        cplChart.update();

                        cplChart2.data.datasets[0].data = r.bobot;
                        cplChart2.data.labels = r.cpl;
                        cplChart2.update();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                    }
                });
            });
            $('#select2semesterMhs').select2().on("change", function(e) {
                console.log($(this).val())
                let semester = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.cpl.getLabelCPLChartMhsBySemester') }}",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'semester': semester
                    },
                    success: function(r) {
                        console.log(r);
                        cplChartMhs.data.datasets[0].data = r.bobot;
                        cplChartMhs.data.labels = r.cpl;
                        cplChartMhs.options.scales.y.max = r.max_bobot + 0.5;
                        cplChartMhs.update();

                        cplChartMhs2.data.datasets[0].data = r.bobot;
                        cplChartMhs2.data.labels = r.cpl;
                        cplChartMhs2.update();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                    }
                });
            });
        });
    </script>
@endpush
