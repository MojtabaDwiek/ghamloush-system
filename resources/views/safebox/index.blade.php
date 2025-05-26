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
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Karat</th>
                        <th>Balance (g)</th>
                        <th>Description</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($safeboxes as $safebox)
                        <tr>
                            <td>
                                <strong>{{ $safebox->name }}</strong>
                                <div class="text-muted small">ID: {{ $safebox->id }}</div>
                            </td>
                            <td>{{ $safebox->karat }}K</td>
                            <td class="{{ $safebox->balance < 0 ? 'text-danger' : '' }}">
                                {{ number_format($safebox->balance, 2) }}
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 200px;">
                                    {{ $safebox->description ?? '-' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('safebox.show', $safebox) }}" 
                                   class="btn btn-sm btn-info"
                                   title="View details">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No safeboxes found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($safeboxes->hasPages())
        <div class="card-footer">
            {{ $safeboxes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection