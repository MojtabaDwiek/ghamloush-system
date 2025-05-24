@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>{{ isset($workOrder) ? 'Edit' : 'Create' }} Work Order</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('work-orders.index') }}" class="btn btn-secondary">
            Back to List
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($workOrder) ? route('work-orders.update', $workOrder) : route('work-orders.store') }}" method="POST">
            @csrf
            @if(isset($workOrder))
                @method('PUT')
            @endif
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type_id" class="form-label">Work Type</label>
                        <select class="form-select" id="type_id" name="type_id" required>
                            <option value="">Select Type</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" 
                                    {{ old('type_id', $workOrder->type_id ?? '') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select class="form-select" id="employee_id" name="employee_id" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" 
                                    {{ old('employee_id', $workOrder->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="safebox_id" class="form-label">Safebox</label>
                        <select class="form-select" id="safebox_id" name="safebox_id" required>
                            <option value="">Select Safebox</option>
                            @foreach($safeboxes as $safebox)
                                <option value="{{ $safebox->id }}" 
                                    {{ old('safebox_id', $workOrder->safebox_id ?? '') == $safebox->id ? 'selected' : '' }}>
                                    {{ $safebox->karat }}K ({{ number_format($safebox->balance, 2) }}g)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="start_amount" class="form-label">Start Amount (g)</label>
                        <input type="number" step="0.01" min="0.01" class="form-control" id="start_amount" 
                               name="start_amount" value="{{ old('start_amount', $workOrder->start_amount ?? '') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="finish_amount" class="form-label">Finish Amount (g) - Optional</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="finish_amount" 
                               name="finish_amount" value="{{ old('finish_amount', $workOrder->finish_amount ?? '') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ old('start_date', isset($workOrder) ? $workOrder->start_date->format('Y-m-d') : date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date - Optional</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ old('end_date', isset($workOrder) && $workOrder->end_date ? $workOrder->end_date->format('Y-m-d') : '' }}">
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                {{ isset($workOrder) ? 'Update' : 'Create' }} Work Order
            </button>
        </form>
    </div>
</div>
@endsection