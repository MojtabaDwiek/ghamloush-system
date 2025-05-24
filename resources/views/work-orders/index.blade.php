@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Work Orders</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('work-orders.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create Work Order
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Employee</th>
                        <th>Safebox</th>
                        <th>Start Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ \App\Constants\WorkOrderTypes::TYPES[$order->type] ?? $order->type }}</td>
                            <td>{{ $order->employee->name }}</td>
                            <td>{{ $order->safebox->karat }}K</td>
                            <td>{{ number_format($order->start_amount, 2) }}g</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('work-orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($order->status === 'pending')
                                        <a href="{{ route('work-orders.edit', $order) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No work orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($workOrders->hasPages())
            <div class="mt-3 d-flex justify-content-center">
                {{ $workOrders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection