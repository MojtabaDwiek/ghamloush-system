@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Safebox Details - {{ $safebox->name }} ({{ $safebox->karat }}K)</h2>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group">
            <a href="{{ route('safebox.deposit.create', $safebox) }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Deposit
            </a>
            <a href="{{ route('safebox.withdraw.create', $safebox) }}" class="btn btn-warning">
                <i class="bi bi-dash-circle"></i> Withdraw
            </a>
            <a href="{{ route('safebox.transfer.create', $safebox) }}" class="btn btn-info">
                <i class="bi bi-arrow-left-right"></i> Transfer
            </a>
        </div>
        <a href="{{ route('safebox.index') }}" class="btn btn-secondary ms-2">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">Current Balance</h5>
                        <h3 class="card-text {{ $safebox->balance < 0 ? 'text-danger' : '' }}">
                            {{ number_format($safebox->balance, 2) }}g
                        </h3>
                        <div class="mt-2">
                            <span class="badge bg-{{ $safebox->balance < 0 ? 'danger' : 'success' }}">
                                {{ $safebox->balance < 0 ? 'Negative Balance' : 'Positive Balance' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Safebox Name</th>
                        <td>{{ $safebox->name }}</td>
                    </tr>
                    <tr>
                        <th>Karat</th>
                        <td>{{ $safebox->karat }}K</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $safebox->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $safebox->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated</th>
                        <td>{{ $safebox->updated_at->format('M d, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <h4 class="mb-3">
            <i class="bi bi-list-check"></i> Transaction History
            <span class="badge bg-secondary">{{ $transactions->total() }}</span>
        </h4>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th class="text-end">Amount (g)</th>
                        <th>Description</th>
                        <th>To/From</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $transaction->type === 'deposit' ? 'success' : 
                                    ($transaction->type === 'withdrawal' ? 'warning' : 'info') 
                                }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="text-end {{ $transaction->type === 'withdrawal' ? 'text-danger' : 'text-success' }}">
                                {{ ($transaction->type === 'withdrawal' ? '-' : '+') . number_format($transaction->amount, 2) }}
                            </td>
                            <td>{{ $transaction->description ?? '-' }}</td>
                            <td>
                                @if($transaction->type === 'transfer')
                                    <a href="{{ route('safebox.show', $transaction->toSafebox) }}">
                                        {{ $transaction->toSafebox->name }} ({{ $transaction->toSafebox->karat }}K)
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions instanceof \Illuminate\Pagination\AbstractPaginator && $transactions->hasPages())
        <div class="card-footer">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection