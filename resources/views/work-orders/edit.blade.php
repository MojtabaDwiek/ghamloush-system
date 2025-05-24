@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Edit Work Order</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('work-orders.index') }}" class="btn btn-secondary">
            Back to List
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('work-orders.update', $workOrder) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Work Type</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Select Type</option>
                            @foreach(\App\Constants\WorkOrderTypes::TYPES as $slug => $name)
                                <option value="{{ $slug }}" {{ old('type', $workOrder->type) == $slug ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id', $workOrder->employee_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="safebox_id" class="form-label">Safebox</label>
                        <select class="form-select @error('safebox_id') is-invalid @enderror" id="safebox_id" name="safebox_id" required>
                            <option value="">Select Safebox</option>
                            @foreach($safeboxes as $safebox)
                                <option value="{{ $safebox->id }}" {{ old('safebox_id', $workOrder->safebox_id) == $safebox->id ? 'selected' : '' }}>
                                    {{ $safebox->karat }}K ({{ number_format($safebox->balance, 2) }}g)
                                </option>
                            @endforeach
                        </select>
                        @error('safebox_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="start_amount" class="form-label">Start Amount (g)</label>
                        <input type="number" step="0.01" min="0.01" class="form-control @error('start_amount') is-invalid @enderror" 
                               id="start_amount" name="start_amount" 
                               value="{{ old('start_amount', $workOrder->start_amount) }}" required>
                        @error('start_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="finish_amount" class="form-label">Finish Amount (g) - Optional</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('finish_amount') is-invalid @enderror" 
                               id="finish_amount" name="finish_amount" 
                               value="{{ old('finish_amount', $workOrder->finish_amount) }}">
                        @error('finish_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                               id="start_date" name="start_date" 
                               value="{{ old('start_date', $workOrder->start_date ? \Carbon\Carbon::parse($workOrder->start_date)->format('Y-m-d') : '') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date - Optional</label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date" name="end_date" 
                               value="{{ old('end_date', $workOrder->end_date ? \Carbon\Carbon::parse($workOrder->end_date)->format('Y-m-d') : '') }}">
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                Update Work Order
            </button>
        </form>
    </div>
</div>
@endsection