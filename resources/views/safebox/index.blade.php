@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Safeboxes</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('safebox.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Safebox
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
                        <th>Karat</th>
                        <th>Balance (g)</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($safeboxes as $safebox)
                        <tr>
                            <td>{{ $safebox->id }}</td>
                            <td>{{ $safebox->karat }}K</td>
                            <td>{{ number_format($safebox->balance, 2) }}</td>
                            <td>{{ $safebox->description ?? '-' }}</td>
                            <td>
                                <a href="{{ route('safebox.show', $safebox) }}" class="btn btn-sm btn-info">
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