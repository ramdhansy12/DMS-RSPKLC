@extends('layouts.app')
@section('title', 'Dashboard DMS')

@section('content')

    <div class="container-fluid">
        <h4 class="mb-4 fw-bold">Dashboard Document Management System</h4>

        <!-- KPI CARDS -->
        <div class="row mb-4">
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6>Total Dokumen</h6>
                        <h3 class="fw-bold">{{ $totalDocuments }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="card shadow-sm border-0 bg-success text-white h-100">
                    <div class="card-body">
                        <h6>Dokumen Aktif</h6>
                        <h3 class="fw-bold">{{ $activeDocuments }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="card shadow-sm border-0 bg-danger text-white h-100">
                    <div class="card-body">
                        <h6>Dokumen Non Aktif</h6>
                        <h3 class="fw-bold">{{ $nonActiveDocuments }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHARTS -->
        <div class="row">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header fw-semibold">
                        Dokumen per Unit
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:300px;">
                            <canvas id="unitChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header fw-semibold">
                        Dokumen Per Kategori
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:300px;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header fw-semibold">
                        Status Dokumen
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:300px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MONTHLY UPLOAD -->
            <div class="col-12 col-lg-8 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header fw-semibold">
                        Upload Dokumen per Bulan ({{ date('Y') }})
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:300px;">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Common chart options for responsiveness
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        responsive: true,
                        labels: {
                            boxWidth: 10,
                            padding: 15
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            maxTicksLimit: 5
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                }
            };

            // BAR CHART - PER UNIT
            new Chart(document.getElementById('unitChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($documentsPerUnit->keys()) !!},
                    datasets: [{
                        label: 'Jumlah Dokumen',
                        data: {!! json_encode($documentsPerUnit->values()) !!},
                        borderWidth: 1,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)'
                    }]
                },
                options: commonOptions
            });

            //Bar Chart - Per Kategori
            new Chart(document.getElementById('categoryChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($documentsPerCategory->keys()) !!},
                    datasets: [{
                        label: 'Jumlah Dokumen',
                        data: {!! json_encode($documentsPerCategory->values()) !!},
                        borderWidth: 1,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)'
                    }]
                },
                options: commonOptions
            });

            // PIE CHART - STATUS
            new Chart(document.getElementById('statusChart'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode($documentsByStatus->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($documentsByStatus->values()) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                padding: 15
                            }
                        }
                    }
                }
            });

            // LINE CHART - MONTHLY
            new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode(range(1, 12)) !!},
                    datasets: [{
                        label: 'Upload Dokumen',
                        data: {!! json_encode($monthlyUpload) !!},
                        fill: false,
                        tension: 0.3,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)'
                    }]
                },
                options: commonOptions
            });

            // Make charts responsive on window resize
            window.addEventListener('resize', function() {
                Chart.helpers.each(Chart.instances, function(instance) {
                    instance.resize();
                });
            });
        });
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
