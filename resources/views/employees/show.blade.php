@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Employee Details</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">
            Edit
        </a>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
            Back to List
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $employee->id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $employee->name }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $employee->phone }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <h4 class="mt-4">Work Orders</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Type</th>
                        <th>Start Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employee->workOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->type->name }}</td>
                            <td>{{ number_format($order->start_amount, 2) }}g</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('work-orders.show', $order) }}" class="btn btn-sm btn-info">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection