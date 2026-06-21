@extends('layouts.app')

@section('content')
<h2 class="mb-4">Grafik Rekomendasi</h2>
<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>Bar Chart — Skor Mata Kuliah Direkomendasikan</strong></div>
            <div class="card-body"><canvas id="barChart" height="120"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>Pie Chart — Kategori MK</strong></div>
            <div class="card-body"><canvas id="pieChart"></canvas></div>
        </div>
    </div>
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>Line Chart — Riwayat IP & IPK per Semester</strong></div>
            <div class="card-body"><canvas id="lineChart" height="80"></canvas></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const barLabels = @json($barLabels);
const barData = @json($barData);
const kategoriChart = @json($kategoriChart);
const ipChart = @json($ipChart);

new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: barLabels,
        datasets: [{ label: 'Skor', data: barData, backgroundColor: 'rgba(13, 110, 253, 0.7)' }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: kategoriChart.labels,
        datasets: [{ data: kategoriChart.data, backgroundColor: ['#0d6efd','#6610f2','#198754','#dc3545','#fd7e14','#20c997'] }]
    },
    options: { responsive: true }
});

new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: ipChart.labels,
        datasets: [
            { label: 'IP Semester', data: ipChart.ipData, borderColor: '#0d6efd', tension: 0.3 },
            { label: 'IPK Kumulatif', data: ipChart.ipkData, borderColor: '#198754', tension: 0.3 }
        ]
    },
    options: { responsive: true, scales: { y: { min: 0, max: 4 } } }
});
</script>
@endpush
