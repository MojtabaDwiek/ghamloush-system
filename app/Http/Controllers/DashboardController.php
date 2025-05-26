<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Safebox;
use App\Constants\WorkOrderTypes;

class DashboardController extends Controller
{
    public function index()
    {
        // Get work orders grouped by type with counts and names
        $workOrders = WorkOrder::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'type' => $item->type,
                    'name' => WorkOrderTypes::TYPES[$item->type] ?? 'Unknown',
                    'count' => $item->count
                ];
            });
            
        // Get recent work orders with required relationships
        $recentWorkOrders = WorkOrder::with(['employee', 'safebox'])
            ->latest()
            ->take(5)
            ->get();
            
        // Get safeboxes with their IDs for routing
        $safeboxBalances = Safebox::with([])->get(); // Empty with() ensures we get all columns
            
        return view('dashboard', compact('workOrders', 'recentWorkOrders', 'safeboxBalances'));
    }
}