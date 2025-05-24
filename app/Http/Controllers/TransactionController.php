<?php

namespace App\Http\Controllers;

use App\Models\Safebox;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function createDeposit(Safebox $safebox)
    {
        return view('transactions.deposit', compact('safebox'));
    }

    public function storeDeposit(Request $request, Safebox $safebox)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ]);
        
        // Create transaction
        $transaction = Transaction::create([
            'safebox_id' => $safebox->id,
            'type' => 'deposit',
            'amount' => $request->amount,
            'karat' => $safebox->karat,
            'description' => $request->description,
        ]);
        
        // Update safebox balance
        $safebox->increment('balance', $request->amount);
        
        return redirect()->route('safebox.show', $safebox)
            ->with('success', 'Deposit recorded successfully.');
    }

    public function createWithdrawal(Safebox $safebox)
    {
        return view('transactions.withdraw', compact('safebox'));
    }

    public function storeWithdrawal(Request $request, Safebox $safebox)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $safebox->balance,
            'description' => 'nullable|string',
        ]);
        
        // Create transaction
        $transaction = Transaction::create([
            'safebox_id' => $safebox->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'karat' => $safebox->karat,
            'description' => $request->description,
        ]);
        
        // Update safebox balance
        $safebox->decrement('balance', $request->amount);
        
        return redirect()->route('safebox.show', $safebox)
            ->with('success', 'Withdrawal recorded successfully.');
    }

    public function createTransfer(Safebox $safebox)
    {
        $otherSafeboxes = Safebox::where('id', '!=', $safebox->id)->get();
        return view('transactions.transfer', compact('safebox', 'otherSafeboxes'));
    }

    public function storeTransfer(Request $request, Safebox $safebox)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $safebox->balance,
            'to_safebox_id' => 'required|exists:safeboxes,id|different:safebox_id',
            'description' => 'nullable|string',
        ]);
        
        $toSafebox = Safebox::find($request->to_safebox_id);
        
        // Create transaction for source safebox
        $transaction = Transaction::create([
            'safebox_id' => $safebox->id,
            'type' => 'transfer',
            'amount' => $request->amount,
            'karat' => $safebox->karat,
            'to_safebox_id' => $toSafebox->id,
            'description' => $request->description,
        ]);
        
        // Create transaction for destination safebox
        Transaction::create([
            'safebox_id' => $toSafebox->id,
            'type' => 'transfer',
            'amount' => $request->amount,
            'karat' => $toSafebox->karat,
            'to_safebox_id' => $safebox->id,
            'description' => $request->description,
        ]);
        
        // Update balances
        $safebox->decrement('balance', $request->amount);
        $toSafebox->increment('balance', $request->amount);
        
        return redirect()->route('safebox.show', $safebox)
            ->with('success', 'Transfer recorded successfully.');
    }
}