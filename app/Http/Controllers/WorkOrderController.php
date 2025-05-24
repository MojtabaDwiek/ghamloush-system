<?php
namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Employee;
use App\Models\Safebox;
use App\Models\WorkOrderChange;
use App\Constants\WorkOrderTypes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkOrderController extends Controller
{
    public function index()
{
    $workOrders = WorkOrder::with(['employee', 'safebox'])
        ->latest()
        ->paginate(10); // Changed from get() to paginate()
    
    return view('work-orders.index', compact('workOrders'));
}

    public function create()
    {
        $employees = Employee::all();
        $safeboxes = Safebox::all();
        
        return view('work-orders.create', [
            'employees' => $employees,
            'safeboxes' => $safeboxes,
            'types' => WorkOrderTypes::TYPES
        ]);
    }

    public function store(Request $request)
    {
        $validTypes = array_keys(WorkOrderTypes::TYPES);
        
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'safebox_id' => 'required|exists:safeboxes,id',
            'type' => ['required', Rule::in($validTypes)],
            'start_amount' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
        ]);
        
        $workOrder = WorkOrder::create([
            'employee_id' => $request->employee_id,
            'safebox_id' => $request->safebox_id,
            'type' => $request->type,
            'start_amount' => $request->start_amount,
            'start_date' => $request->start_date,
            'status' => 'pending'
        ]);
        
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
        $workOrder->load(['employee', 'safebox', 'changes.safebox']);
        return view('work-orders.show', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder)
    {
        if ($workOrder->status === 'completed') {
            return redirect()->back()->with('error', 'Completed work orders cannot be edited.');
        }
        
        return view('work-orders.edit', [
            'workOrder' => $workOrder,
            'employees' => Employee::all(),
            'safeboxes' => Safebox::all(),
            'types' => WorkOrderTypes::TYPES
        ]);
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        if ($workOrder->status === 'completed') {
            return redirect()->back()->with('error', 'Completed work orders cannot be edited.');
        }
        
        $validTypes = array_keys(WorkOrderTypes::TYPES);
        
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'safebox_id' => 'required|exists:safeboxes,id',
            'type' => ['required', Rule::in($validTypes)],
            'start_amount' => 'required|numeric|min:0.01',
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
        if ($workOrder->type != $request->type) {
            $changes[] = 'Type changed from ' . $workOrder->type . ' to ' . $request->type;
        }
        
        if (!empty($changes)) {
            WorkOrderChange::create([
                'work_order_id' => $workOrder->id,
                'safebox_id' => $request->safebox_id,
                'weight' => $request->start_amount,
                'note' => implode(', ', $changes)
            ]);
        }
        
        $workOrder->update([
            'employee_id' => $request->employee_id,
            'safebox_id' => $request->safebox_id,
            'type' => $request->type,
            'start_amount' => $request->start_amount,
            'finish_amount' => $request->finish_amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        
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
        if ($workOrder->type === 'melting') {
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

   public function byType($type)
{
    if (!array_key_exists($type, WorkOrderTypes::TYPES)) {
        abort(404);
    }
    
    $workOrders = WorkOrder::where('type', $type)
        ->with(['employee', 'safebox'])
        ->latest()
        ->paginate(10);
        
    return view('work-orders.by-type', [
        'workOrders' => $workOrders,
        'type' => $type,
        'typeName' => WorkOrderTypes::TYPES[$type] // Add the display name
    ]);
}
}