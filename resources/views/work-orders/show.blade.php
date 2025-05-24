@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Work Order #{{ $workOrder->id }}</h2>
        <h5 class="text-muted">{{ $workOrder->type->name }}</h5>
    </div>
    <div class="col-md-6 text-end">
        @if($workOrder->status === 'pending')
            <form action="{{ route('work-orders.complete', $workOrder) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success" 
                    {{ !$workOrder->finish_amount ? 'disabled' : '' }}>
                    Complete Order
                </button>
            </form>
            <a href="{{ route('work-orders.edit', $workOrder) }}" class="btn btn-warning">
                Edit
            </a>
        @endif
        <a href="{{ route('work-orders.index') }}" class="btn btn-secondary">
            Back to List
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $workOrder->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($workOrder->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Employee</th>
                        <td>{{ $workOrder->employee->name }}</td>
                    </tr>
                    <tr>
                        <th>Safebox</th>
                        <td>{{ $workOrder->safebox->karat }}K</td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td>{{ $workOrder->start_date->format('Y-m-d') }}</td>
                    </tr>
                    @if($workOrder->end_date)
                        <tr>
                            <th>End Date</th>
                            <td>{{ $workOrder->end_date->format('Y-m-d') }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Start Amount</th>
                        <td>{{ number_format($workOrder->start_amount, 2) }}g</td>
                    </tr>
                    @if($workOrder->finish_amount)
                        <tr>
                            <th>Finish Amount</th>
                            <td>{{ number_format($workOrder->finish_amount, 2) }}g</td>
                        </tr>
                        <tr>
                            <th>Loss</th>
                            <td>
                                {{ number_format($workOrder->loss, 2) }}g
                                @if($workOrder->type->slug === 'melting')
                                    <span class="text-muted">(50% of actual loss)</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
        
        <h4>Change History</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Safebox</th>
                        <th>Weight (g)</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workOrder->changes as $change)
                        <tr>
                            <td>{{ $change->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $change->safebox->karat }}K</td>
                            <td>{{ number_format($change->weight, 2) }}</td>
                            <td>{{ $change->note }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection