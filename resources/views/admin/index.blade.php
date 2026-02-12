@extends('admin.layout.master')

@section('title')
    داشبورد مدیریت
@endsection

@section('content')

    <!-- CDN ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>

    <style>
        .dashboard-card {
            border: none;
            border-radius: 15px;
            transition: 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .dashboard-icon {
            font-size: 40px;
            opacity: 0.8;
        }

        .bg-gradient-warning {
            background: linear-gradient(45deg, #f59f00, #f76707);
        }

        .bg-gradient-success {
            background: linear-gradient(45deg, #2f9e44, #40c057);
        }

        .bg-gradient-primary {
            background: linear-gradient(45deg, #364fc7, #4c6ef5);
        }

        .bg-gradient-dark {
            background: linear-gradient(45deg, #343a40, #495057);
        }
    </style>

    <div class="container-fluid mt-4">
        {{-- کارت‌های آماری --}}
        <div class="row g-4 mb-4">

            <div class="col-md-3">
                <div class="card dashboard-card text-white bg-gradient-warning shadow-lg">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">در انتظار ارسال</h6>
                            <h2 class="fw-bold">{{ $pendingPaidOrders }}</h2>
                            <small>سفارش پرداخت شده</small>
                        </div>
                        <div>
                            <i class="bi bi-hourglass-split dashboard-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card text-white bg-gradient-success shadow-lg">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">ارسال شده</h6>
                            <h2 class="fw-bold">{{ $sentPaidOrders }}</h2>
                            <small>سفارش پرداخت شده</small>
                        </div>
                        <div>
                            <i class="bi bi-truck dashboard-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card text-white bg-gradient-primary shadow-lg">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">کل سفارشات پرداخت شده</h6>
                            <h2 class="fw-bold">{{ $totalPaidOrders }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-credit-card dashboard-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card dashboard-card text-white bg-gradient-dark shadow-lg">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-2">کل محصولات</h6>
                            <h2 class="fw-bold">{{ $totalProducts }}</h2>
                        </div>
                        <div>
                            <i class="bi bi-box-seam dashboard-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- نمودارها --}}
        <div class="row g-4">
            {{-- نمودار میزان فروش --}}
            <div class="col-lg-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            میزان فروش به تفکیک دسته‌بندی (تومان)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="salesChart" style="height: 450px;"></div>
                    </div>
                </div>
            </div>

            {{-- نمودار تعداد فروش --}}
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-cart-check me-2"></i>
                            تعداد فروش به تفکیک دسته‌بندی (عدد)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="countChart" style="height: 450px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // منتظر بمون تا DOM کامل لود بشه
        window.addEventListener('load', function() {
            console.log('Starting chart initialization...');

            // داده‌های نمودار میزان فروش
            const salesData = {!! json_encode($salesData) !!};
            console.log('Sales Data:', salesData);

            if (salesData.series.length > 0) {
                const salesOptions = {
                    series: salesData.series,
                    chart: {
                        type: 'line',
                        height: 450,
                        fontFamily: 'IRANSans, sans-serif',
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                                zoom: true,
                                zoomin: true,
                                zoomout: true,
                                pan: true,
                                reset: true
                            }
                        },
                        zoom: {
                            enabled: true
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    dataLabels: {
                        enabled: false
                    },
                    markers: {
                        size: 4,
                        hover: {
                            size: 7
                        }
                    },
                    xaxis: {
                        categories: salesData.categories,
                        labels: {
                            rotate: -45,
                            rotateAlways: true,
                            style: {
                                fontSize: '11px'
                            }
                        },
                        tickPlacement: 'on'
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return value.toLocaleString('fa-IR') + ' تومان';
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (value) {
                                return value.toLocaleString('fa-IR') + ' تومان';
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        fontSize: '14px',
                        markers: {
                            width: 12,
                            height: 12
                        }
                    },
                    grid: {
                        borderColor: '#e7e7e7',
                        row: {
                            colors: ['#f3f3f3', 'transparent'],
                            opacity: 0.5
                        }
                    }
                };

                const salesChart = new ApexCharts(document.querySelector("#salesChart"), salesOptions);
                salesChart.render();
                console.log('Sales chart rendered!');
            } else {
                document.querySelector("#salesChart").innerHTML = '<div class="text-center p-5"><p class="text-muted">داده‌ای برای نمایش وجود ندارد</p></div>';
            }

            // داده‌های نمودار تعداد فروش
            const countData = {!! json_encode($countData) !!};
            console.log('Count Data:', countData);

            if (countData.series.length > 0) {
                const countOptions = {
                    series: countData.series,
                    chart: {
                        type: 'bar',
                        height: 450,
                        fontFamily: 'IRANSans, sans-serif',
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                                zoom: true,
                                zoomin: true,
                                zoomout: true,
                                pan: true,
                                reset: true
                            }
                        },
                        zoom: {
                            enabled: true
                        }
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '55%',
                            distributed: false,
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        categories: countData.categories,
                        labels: {
                            rotate: -45,
                            rotateAlways: true,
                            style: {
                                fontSize: '11px'
                            }
                        },
                        tickPlacement: 'on'
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return Math.round(value).toLocaleString('fa-IR');
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (value) {
                                return Math.round(value).toLocaleString('fa-IR') + ' عدد';
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        fontSize: '14px',
                        markers: {
                            width: 12,
                            height: 12
                        }
                    },
                    grid: {
                        borderColor: '#e7e7e7'
                    }
                };

                const countChart = new ApexCharts(document.querySelector("#countChart"), countOptions);
                countChart.render();
                console.log('Count chart rendered!');
            } else {
                document.querySelector("#countChart").innerHTML = '<div class="text-center p-5"><p class="text-muted">داده‌ای برای نمایش وجود ندارد</p></div>';
            }
        });
    </script>

@endsection
