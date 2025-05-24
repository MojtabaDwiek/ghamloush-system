@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Safebox Details - {{ $safebox->karat }}K</h2>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group">
            <a href="{{ route('safebox.deposit.create', $safebox) }}" class="btn btn-success">
                Deposit
            </a>
            <a href="{{ route('safebox.withdraw.create', $safebox) }}" class="btn btn-warning">
                Withdraw
            </a>
            <a href="{{ route('safebox.transfer.create', $safebox) }}" class="btn btn-info">
                Transfer
            </a>
        </div>
        <a href="{{ route('safebox.index') }}" class="btn btn-secondary ms-2">
            Back to List
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
                        <h3 class="card-text">{{ number_format($safebox->balance, 2) }}g</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <table class="table table-bordered">
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
                        <td>{{ $safebox->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <h4>Transactions</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount (g)</th>
                        <th>Description</th>
                        <th>To/From</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
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
                            <td>{{ number_format($transaction->amount, 2) }}</td>
                            <td>{{ $transaction->description ?? '-' }}</td>
                            <td>
                                @if($transaction->type === 'transfer')
                                    {{ $transaction->toSafebox->karat }}K ({{ $transaction->toSafebox->id }})
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection