@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Transfer from {{ $safebox->karat }}K Safebox</h2>
            <p class="text-muted">Current Balance: {{ number_format($safebox->balance, 2) }}g</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('safebox.show', $safebox) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Safebox
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('safebox.transfer.store', $safebox) }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount to Transfer (grams)</label>
                    <input type="number" step="0.01" min="0.01" max="{{ $safebox->balance }}" 
                           class="form-control @error('amount') is-invalid @enderror" 
                           id="amount" name="amount" value="{{ old('amount') }}" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Maximum transferable: {{ $safebox->balance }}g</small>
                </div>
                
                <div class="mb-3">
                    <label for="to_safebox_id" class="form-label">Destination Safebox</label>
                    <select class="form-select @error('to_safebox_id') is-invalid @enderror" 
                            id="to_safebox_id" name="to_safebox_id" required>
                        <option value="">Select a safebox</option>
                        @foreach($otherSafeboxes as $other)
                            <option value="{{ $other->id }}" {{ old('to_safebox_id') == $other->id ? 'selected' : '' }}>
                                {{ $other->karat }}K Safebox (Balance: {{ $other->balance }}g)
                            </option>
                        @endforeach
                    </select>
                    @error('to_safebox_id')
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
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-left-right"></i> Confirm Transfer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection