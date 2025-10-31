@extends('dashboard.layout.main')

@section('content')
    <div class="p-3">
        <section class="section dashboard">
            <div class="row align-content-center justify-content-center">
                <div class="col-lg-11">
                    <div class="row">
                        <div class="col-xxl-4 col-md-4">
                            <div class="card info-card sales-card">
                                <div class="filter">
                                    <select class="custom-select select form-control" name="call_filter" id="call_filter">
                                        <option value="Today">Today</option>
                                        <option value="Month">This Month</option>
                                        <option value="Year" selected>This Year</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Calls </h5>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <small id="count_calls">145</small>
                                        </div>
                                        <i class="nav-icon fas fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-4">
                            <div class="card info-card sales-card">
                                <div class="filter">
                                    <select class="custom-select select form-control" name="visit_filter" id="visit_filter">
                                        <option value="Today">Today</option>
                                        <option value="Month">This Month</option>
                                        <option value="Year" selected>This Year</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Visits</h5>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <small id="count_visits">145</small>
                                        </div>
                                        <i class="nav-icon fas fa-house-user"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-4">
                            <div class="card info-card customers-card">
                                <div class="filter">
                                    <select class="custom-select select form-control" id="patients_filter">
                                        <option value="Today">Today</option>
                                        <option value="Month">This Month</option>
                                        <option value="Year" selected>This Year</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Enroll Patient </h5>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <small id="patients">1244</small>
                                        </div>
                                        <i class="nav-icon fas fa-user-plus"></i>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="col-xxl-4 col-md-4">
                            <div class="card info-card sales-card">

                                <div class="filter">
                                    <select class="custom-select select form-control" id="programs_filter">
                                        <option value="Today">Today</option>
                                        <option value="Month">This Month</option>
                                        <option value="Year" selected>This Year</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Programs </h5>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <small id="programs">145</small>
                                        </div>
                                        <i class="nav-icon fas fa-object-group"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-4">
                            <div class="card info-card sales-card">

                                <div class="filter">
                                    <select class="custom-select select form-control" id="sub_programs_filter">
                                        <option value="Today">Today</option>
                                        <option value="Month">This Month</option>
                                        <option value="Year" selected>This Year</option>
                                    </select>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Sub Programs </h5>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <small id="sub_programs">145</small>
                                        </div>
                                        <i class="nav-icon fas fa-cubes"></i>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="filter">
                                    <select class="custom-select select form-control" id="clients_filter">
                                        <option value="Today">Today</option>
                                        <option value="Month">This Month</option>
                                        <option value="Year" selected>This Year</option>
                                    </select>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Clients</h5>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <small id="clients">1244</small>
                                        </div>
                                        <i class="nav-icon fas fa-users"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-4">
                            <div class="card">
                                <div class="filter">
                                    <select class="custom-select select form-control" id="trafficChart_filter">
                                        <option value="Today">Today</option>
                                        <option value="Month">This Month</option>
                                        <option value="Year" selected>This Year</option>
                                    </select>
                                </div>

                                <div class="card-body pb-0">
                                    <h5 class="card-title">Visits</h5>

                                    <div id="trafficChart" style="min-height: 384px;" class="echart"></div>

                                </div>
                            </div>


                        </div>
                       <div class="col-xxl-8 col-xl-8">
                            <div class="card">

                                <div class="filter">
                                    <select class="custom-select select form-control" id="apexcharts">
                                        <option value="Today">Today</option>
                                        <option value="Month">This Month</option>
                                        <option value="Year" selected>This Year</option>
                                    </select>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Reports</h5>
                                    <div id="reportsChart"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    function loadStatistics(page) {
        const visit_filter = $('#visit_filter').val();
        const call_filter = $('#call_filter').val();
        const visitsFOC_filter = $('#visitsFOC_filter').val();
        const trafficChart_filter = $('#trafficChart_filter').val();

        const programs_filter = $('#programs_filter').val();
        const sub_programs_filter = $('#sub_programs_filter').val();
        const patients_filter = $('#patients_filter').val();
        const clients_filter = $('#clients_filter').val();

      const apexcharts = $('#apexcharts').val();

        $.ajax({
            url: '?page=' + page,
            type: 'get',
            data: {
                visit_filter: visit_filter,
                call_filter: call_filter,
                trafficChart_filter: trafficChart_filter,

                programs_filter : programs_filter,
                sub_programs_filter : sub_programs_filter,
                patients_filter : patients_filter,
                clients_filter : clients_filter,
                apexcharts : apexcharts,
            },

            dataType: 'json',
            success: function (data) {
                $('#count_visits').text(data.visits);
                $('#count_calls').text(data.calls);

                $('#programs').text(data.programs);
                $('#patients').text(data.patients);
                $('#clients').text(data.clients);
                $('#sub_programs').text(data.sub_programs);

                var callsApexChartsDataArray = data.callsApexChartsData.map(function (item) {
                    return item.value;
                });
                var visitsFOCApexChartsDataArray = data.visitsFOCApexChartsData.map(function (item) {
                    return item.value;
                });
                var visitsApexChartsDataArray = data.visitsApexChartsData.map(function (item) {
                    return item.value;
                });
                var visitsApexChartsDataArrayLabels = data.visitsApexChartsData.map(function (item) {
                    return item.label;
                });
                echarts.init(document.querySelector("#trafficChart")).setOption({
                    tooltip: {
                        trigger: 'item'
                    },
                    legend: {
                        top: '5%',
                        left: 'center'
                    },
                    series: [{
                        type: 'pie',
                        radius: ['40%', '70%'],
                        avoidLabelOverlap: false,
                        label: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            label: {
                                show: true,
                                fontSize: '18',
                                fontWeight: 'bold'
                            }
                        },
                        labelLine: {
                            show: false
                        },
                        data: [
                            {
                                value: data.visits_rafficChart,
                                name: 'Visits'
                            },
                            {
                                value: data.calls_rafficChart,
                                name: 'Calls'
                            },
                            {
                                value: data.visitsFOC_rafficChart,
                                name: 'FOC'
                            }
                        ]
                    }]
                });

                $('#reportsChart').empty();
                new ApexCharts(document.querySelector("#reportsChart"), {
                    series: [{
                        name: 'Visits',
                        data:visitsApexChartsDataArray,
                    }, {
                        name: 'Calls',
                        data: callsApexChartsDataArray ,
                    }, {
                        name: 'FOC',
                        data: visitsFOCApexChartsDataArray
                    }],
                    chart: {
                        height: 350,
                        type: 'area',
                        toolbar: {
                            show: false
                        },
                    },
                    markers: {
                        size: 4
                    },
                    colors: ['#4154f1', '#2eca6a', '#ff771d'],
                    fill: {
                        type: "gradient",
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.3,
                            opacityTo: 0.4,
                            stops: [0, 90, 100]
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    xaxis: {
                        type: 'datetime',
                        categories: visitsApexChartsDataArrayLabels,
                    },
                    tooltip: {
                        x: {
                            format: 'dd/MM/yy HH:mm'
                        },
                    }
                }).render();

            }
        });
    }
    $(document).ready(function () {
        loadStatistics();
        var filters = '#call_filter, #visit_filter, #visitsFOC_filter, #trafficChart_filter, #clients_filter, #apexcharts, #programs_filter, #sub_programs_filter, #patients_filter';

        $(filters).on('change', function () {
            loadStatistics();
        });
    });
</script>
@endpush
