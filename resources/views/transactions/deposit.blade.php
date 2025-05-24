@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Deposit to Safebox - {{ $safebox->karat }}K</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('safebox.show', $safebox) }}" class="btn btn-secondary">
            Back to Safebox
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('safebox.deposit.store', $safebox) }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="amount" class="form-label">Amount (g)</label>
                <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description (Optional)</label>
                <textarea class="form-control" id="description" name="description" rows="2"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">
                Record Deposit
            </button>
        </form>
    </div>
</div>
@endsection