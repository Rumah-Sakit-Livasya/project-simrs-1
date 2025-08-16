@extends('inc.layout')
@section('title', ' Checklist Harian')
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        <div class="container mt-5">
            <h1 class="mb-4">Waste Management</h1>

            <!-- Grafik -->
            <div class="card mb-4">
                <div class="card-header">Grafik Volume Limbah Harian</div>
                <div class="card-body"><canvas id="wasteChart"></canvas></div>
            </div>

            <!-- Pisahkan ke halaman khusus -->
            <div class="d-flex gap-2 mb-4">
                <a href="{{ url('/daily-waste/daily') }}" class="btn btn-success">Buka Input Harian</a>
                <a href="{{ url('/daily-waste/transport') }}" class="btn btn-info">Buka Input Pengangkutan</a>
            </div>
        </div>


    </main>
    </body>
@endsection
@section('plugin')
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Halaman ini kini hanya berisi navigasi dan grafik

            // ChartJS
            var ctx = document.getElementById('wasteChart').getContext('2d');
            var wasteChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Total Volume Limbah per Hari (Kg)',
                        data: [],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            function updateChart() {
                $.ajax({
                    url: "{{ url('api/chart-data') }}",
                    type: 'GET',
                    success: function(data) {
                        wasteChart.data.labels = data.map(item => item.day);
                        wasteChart.data.datasets[0].data = data.map(item => item.total_volume);
                        wasteChart.update();
                    }
                });
            }

            updateChart();
        });
    </script>
@endsection
