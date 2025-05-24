@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>{{ ucfirst($type->name) }} Work Orders</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('work-orders.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create Work Order
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
                    @foreach($workOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->employee->name }}</td>
                            <td>{{ $order->safebox->karat }}K</td>
                            <td>{{ number_format($order->start_amount, 2) }}g</td>
                            <td>
                                @if($order->finish_amount)
                                    {{ number_format($order->finish_amount, 2) }}g
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
                                <a href="{{ route('work-orders.show', $order) }}" class="btn btn-sm btn-info">
                                    View
                                </a>
                                @if($order->status === 'pending')
                                    <a href="{{ route('work-orders.edit', $order) }}" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection