@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6" x-data="dashboardData()">
    <h1 class="text-2xl font-bold mb-6">📊 Dashboard - Sistema de Licencias</h1>
    

    {{-- GRAFICOS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Doughnut Aprobados / No Aprobados --}}
        <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
            <h2 class="text-lg font-semibold mb-2">Resultado Exámenes</h2>
            <canvas id="doughnutChart" class="w-full h-64"></canvas>
        </div>

        {{-- Line Postulantes últimos 5 días --}}
        <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
            <h2 class="text-lg font-semibold mb-2">Postulantes últimos 5 días</h2>
            <canvas id="lineChart" class="w-full h-64"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function dashboardData() {
    return {
        init() {
            this.fetchData();
        },
        fetchData() {
            fetch("{{ route('dashboard.data') }}")
                .then(res => res.json())
                .then(data => {
                    this.renderDoughnut(data.aprobados, data.noAprobados);
                    this.renderLine(data.fechas, data.totales);
                })
                .catch(err => console.error("Error al cargar datos del dashboard", err));
        },
        renderDoughnut(aprobados, noAprobados) {
            const ctx = document.getElementById('doughnutChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Aprobados', 'No Aprobados'],
                    datasets: [{
                        data: [aprobados, noAprobados],
                        backgroundColor: ['#16A34A', '#DC2626']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a,b)=>a+b,0);
                                    const value = context.parsed;
                                    const porcentaje = total ? ((value/total)*100).toFixed(1) : 0;
                                    return context.label + ': ' + value + ' (' + porcentaje + '%)';
                                }
                            }
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },
        renderLine(labels, dataPoints) {
            const ctx = document.getElementById('lineChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Postulantes inscritos',
                        data: dataPoints,
                        fill: false,
                        borderColor: '#2563EB',
                        backgroundColor: '#2563EB',
                        tension: 0.3,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true }
                    },
                    scales: {
                        y: { beginAtZero: true, stepSize: 1 },
                        x: { ticks: { autoSkip: false } }
                    }
                }
            });
        }
    }
}
</script>
@endpush
