@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Dashboard</h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Work Orders by Type
            </div>
            <div class="card-body">
                <canvas id="workOrdersChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Safebox Balances by Karat
            </div>
            <div class="card-body">
                <canvas id="safeboxChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Recent Work Orders
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Employee</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\WorkOrder::with(['type', 'employee'])->latest()->take(5)->get() as $order)
                                <tr>
                                    <td><a href="{{ route('work-orders.show', $order) }}">{{ $order->id }}</a></td>
                                    <td>{{ $order->type->name }}</td>
                                    <td>{{ $order->employee->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Safebox Summary
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Karat</th>
                                <th>Balance (g)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\Safebox::all() as $safebox)
                                <tr>
                                    <td>{{ $safebox->karat }}K</td>
                                    <td>{{ number_format($safebox->balance, 2) }}</td>
                                    <td>
                                        <a href="{{ route('safebox.show', $safebox) }}" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Work Orders by Type Chart
    const workOrdersCtx = document.getElementById('workOrdersChart').getContext('2d');
    const workOrdersChart = new Chart(workOrdersCtx, {
        type: 'pie',
        data: {
            labels: @json($workOrders->pluck('type.name')),
            datasets: [{
                data: @json($workOrders->pluck('count')),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                    '#FF9F40', '#8AC24A', '#607D8B', '#E91E63', '#9C27B0'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });

    // Safebox Balances Chart
    const safeboxCtx = document.getElementById('safeboxChart').getContext('2d');
    const safeboxChart = new Chart(safeboxCtx, {
        type: 'bar',
        data: {
            labels: @json($safeboxBalances->pluck('karat')->map(fn($k) => $k . 'K')),
            datasets: [{
                label: 'Balance (g)',
                data: @json($safeboxBalances->pluck('balance')),
                backgroundColor: '#4BC0C0'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection