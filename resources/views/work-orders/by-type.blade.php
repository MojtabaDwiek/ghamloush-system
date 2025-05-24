@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>{{ \App\Constants\WorkOrderTypes::TYPES[$type] ?? ucfirst($type) }} Work Orders</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('work-orders.create', ['type' => $type]) }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create {{ \App\Constants\WorkOrderTypes::TYPES[$type] ?? ucfirst($type) }} Order
        </a>
        <a href="{{ route('work-orders.index') }}" class="btn btn-secondary">
            All Work Orders
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
                        <th>Employee</th>
                        <th>Safebox</th>
                        <th>Start Amount</th>
                        <th>Finish Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->employee->name }}</td>
                            <td>{{ $order->safebox->karat }}K ({{ number_format($order->safebox->balance, 2) }}g)</td>
                            <td>{{ number_format($order->start_amount, 2) }}g</td>
                            <td>
                                @if($order->finish_amount)
                                    {{ number_format($order->finish_amount, 2) }}g
                                    @if($order->loss)
                                        <br><small class="text-danger">Loss: {{ number_format($order->loss, 2) }}g</small>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
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
                                        <form action="{{ route('work-orders.complete', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Mark this order as completed?')">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
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
            <div class="mt-3">
                {{ $workOrders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection