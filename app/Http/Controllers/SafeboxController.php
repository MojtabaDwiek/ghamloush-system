<?php

namespace App\Http\Controllers;

use App\Models\Safebox;
use Illuminate\Http\Request;

class SafeboxController extends Controller
{
    public function index()
    {
        $safeboxes = Safebox::all();
        return view('safebox.index', compact('safeboxes'));
    }

    public function create()
    {
        return view('safebox.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'karat' => 'required|in:18,21,24',
            'description' => 'nullable|string',
        ]);
        
        Safebox::create($request->all());
        
        return redirect()->route('safebox.index')
            ->with('success', 'Safebox created successfully.');
    }

    public function show(Safebox $safebox)
    {
        $transactions = $safebox->transactions()->latest()->get();
        return view('safebox.show', compact('safebox', 'transactions'));
    }
}