@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>{{ isset($employee) ? 'Edit' : 'Add' }} Employee</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
            Back to List
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($employee) ? route('employees.update', $employee) : route('employees.store') }}" method="POST">
            @csrf
            @if(isset($employee))
                @method('PUT')
            @endif
            
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="{{ old('name', $employee->name ?? '') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" 
                       value="{{ old('phone', $employee->phone ?? '') }}" required>
            </div>
            
            <button type="submit" class="btn btn-primary">
                {{ isset($employee) ? 'Update' : 'Save' }}
            </button>
        </form>
    </div>
</div>
@endsection