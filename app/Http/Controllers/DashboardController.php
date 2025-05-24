<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Safebox;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data for charts
        $workOrders = WorkOrder::selectRaw('type_id, count(*) as count')
            ->groupBy('type_id')
            ->with('type')
            ->get();
            
        $safeboxBalances = Safebox::select('karat', 'balance')->get();
        
        return view('dashboard', compact('workOrders', 'safeboxBalances'));
    }
}