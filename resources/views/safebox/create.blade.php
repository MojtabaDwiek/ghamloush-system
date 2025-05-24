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
                <label for="karat" class="form-label">Karat</label>
                <select class="form-select" id="karat" name="karat" required>
                    <option value="">Select Karat</option>
                    <option value="18">18K</option>
                    <option value="21">21K</option>
                    <option value="24">24K</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description (Optional)</label>
                <input type="text" class="form-control" id="description" name="description">
            </div>
            
            <button type="submit" class="btn btn-primary">
                Save
            </button>
        </form>
    </div>
</div>
@endsection