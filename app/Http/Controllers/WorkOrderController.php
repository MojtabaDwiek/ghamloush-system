<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WorkOrderType;
use App\Models\Employee;
use App\Models\Safebox;
use App\Models\WorkOrderChange;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    public function index()
    {
        $workOrders = WorkOrder::with(['employee', 'safebox', 'type'])->latest()->get();
        return view('work-orders.index', compact('workOrders'));
    }

    public function create()
    {
        $types = WorkOrderType::all();
        $employees = Employee::all();
        $safeboxes = Safebox::all();
        
        return view('work-orders.create', compact('types', 'employees', 'safeboxes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'safebox_id' => 'required|exists:safeboxes,id',
            'type_id' => 'required|exists:work_order_types,id',
            'start_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
        ]);
        
        $workOrder = WorkOrder::create($request->all());
        
        // Record initial change
        WorkOrderChange::create([
            'work_order_id' => $workOrder->id,
            'safebox_id' => $workOrder->safebox_id,
            'weight' => $workOrder->start_amount,
            'note' => 'Initial work order creation'
        ]);
        
        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work order created successfully.');
    }

    public function show(WorkOrder $workOrder)
    {
        $workOrder->load(['employee', 'safebox', 'type', 'changes.safebox']);
        return view('work-orders.show', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder)
    {
        if ($workOrder->status === 'completed') {
            return redirect()->back()->with('error', 'Completed work orders cannot be edited.');
        }
        
        $types = WorkOrderType::all();
        $employees = Employee::all();
        $safeboxes = Safebox::all();
        
        return view('work-orders.edit', compact('workOrder', 'types', 'employees', 'safeboxes'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        if ($workOrder->status === 'completed') {
            return redirect()->back()->with('error', 'Completed work orders cannot be edited.');
        }
        
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'safebox_id' => 'required|exists:safeboxes,id',
            'type_id' => 'required|exists:work_order_types,id',
            'start_amount' => 'required|numeric|min:0',
            'finish_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        // Record changes before updating
        $changes = [];
        if ($workOrder->safebox_id != $request->safebox_id) {
            $changes[] = 'Safebox changed from ' . $workOrder->safebox_id . ' to ' . $request->safebox_id;
        }
        if ($workOrder->start_amount != $request->start_amount) {
            $changes[] = 'Start amount changed from ' . $workOrder->start_amount . ' to ' . $request->start_amount;
        }
        if ($workOrder->finish_amount != $request->finish_amount) {
            $changes[] = 'Finish amount changed from ' . ($workOrder->finish_amount ?? 'null') . ' to ' . ($request->finish_amount ?? 'null');
        }
        
        if (!empty($changes)) {
            WorkOrderChange::create([
                'work_order_id' => $workOrder->id,
                'safebox_id' => $request->safebox_id,
                'weight' => $request->start_amount,
                'note' => implode(', ', $changes)
            ]);
        }
        
        $workOrder->update($request->all());
        
        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work order updated successfully.');
    }

    public function complete(WorkOrder $workOrder)
    {
        if ($workOrder->status === 'completed') {
            return redirect()->back()->with('error', 'Work order is already completed.');
        }
        
        if (!$workOrder->finish_amount) {
            return redirect()->back()->with('error', 'Finish amount is required to complete the work order.');
        }
        
        // Calculate loss
        $loss = $workOrder->start_amount - $workOrder->finish_amount;
        
        // For melting, divide loss by 2
        if ($workOrder->type->slug === 'melting') {
            $loss = $loss / 2;
        }
        
        $workOrder->update([
            'loss' => $loss,
            'status' => 'completed',
            'end_date' => $workOrder->end_date ?? now(),
        ]);
        
        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work order marked as completed.');
    }

    public function byType(WorkOrderType $type)
    {
        $workOrders = WorkOrder::where('type_id', $type->id)
            ->with(['employee', 'safebox'])
            ->latest()
            ->get();
            
        return view('work-orders.by-type', compact('workOrders', 'type'));
    }
}