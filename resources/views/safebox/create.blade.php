@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Add Safebox</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('safebox.index') }}" class="btn btn-secondary">
            Back to List
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('safebox.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Safebox Name *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="karat" class="form-label">Karat *</label>
                <select class="form-select @error('karat') is-invalid @enderror" 
                        id="karat" name="karat" required>
                    <option value="">Select Karat</option>
                    <option value="18" {{ old('karat') == '18' ? 'selected' : '' }}>18K</option>
                    <option value="21" {{ old('karat') == '21' ? 'selected' : '' }}>21K</option>
                    <option value="24" {{ old('karat') == '24' ? 'selected' : '' }}>24K</option>
                </select>
                @error('karat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description (Optional)</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Safebox
                </button>
            </div>
        </form>
    </div>
</div>
@endsection