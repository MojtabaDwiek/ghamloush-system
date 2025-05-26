<?php

namespace App\Http\Controllers;

use App\Models\Safebox;
use Illuminate\Http\Request;

class SafeboxController extends Controller
{
    public function index()
{
    $safeboxes = Safebox::orderBy('name')->paginate(10); 
    return view('safebox.index', compact('safeboxes'));
}

    public function create()
    {
        return view('safebox.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:safeboxes',
            'karat' => 'required|in:18,21,24',
            'description' => 'nullable|string',
        ]);
        
        Safebox::create([
            'name' => $request->name,
            'karat' => $request->karat,
            'description' => $request->description,
            'balance' => 0.00 // Initialize with zero balance
        ]);
        
        return redirect()->route('safebox.index')
            ->with('success', 'Safebox created successfully.');
    }

    public function show(Safebox $safebox)
    {
        $transactions = $safebox->transactions()
            ->with(['toSafebox']) // Eager load relationship for transfers
            ->latest()
            ->paginate(10); // Paginate with 10 items per page
            
        return view('safebox.show', compact('safebox', 'transactions'));
    }
}