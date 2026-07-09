<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Fault;

class WorkOrderController extends Controller
{
    public function index()
    {
        $availableFaults = Fault::with(['vehicle', 'reporter'])
                                ->where('status', 'open')
                                ->whereNull('mechanic_id')
                                ->orderByRaw("CASE severity WHEN 'critical' THEN 1 WHEN 'moderate' THEN 2 WHEN 'minor' THEN 3 END")
                                ->get();

        $myFaults = Fault::with(['vehicle', 'reporter'])
                         ->where('mechanic_id', auth()->id())
                         ->whereIn('status', ['open', 'in_progress'])
                         ->orderBy('reported_at', 'desc')
                         ->get();

        $completedFaults = Fault::with(['vehicle', 'reporter'])
                                ->where('mechanic_id', auth()->id())
                                ->where('status', 'resolved')
                                ->orderBy('resolved_at', 'desc')
                                ->take(5)
                                ->get();

        return view('mechanic.mechanicworkorders', compact(
            'availableFaults',
            'myFaults',
            'completedFaults'
        ));
    }

    public function accept($id)
    {
        $fault = Fault::findOrFail($id);

        if ($fault->mechanic_id !== null) {
            return back()->with('error', 'This fault has already been taken.');
        }

        $fault->update([
            'mechanic_id' => auth()->id(),
            'status'      => 'in_progress',
        ]);

        return back()->with('success', 'Fault accepted. You are now responsible for this repair.');
    }

    public function complete($id)
    {
        $fault = Fault::findOrFail($id);

        if ($fault->mechanic_id !== auth()->id()) {
            return back()->with('error', 'You are not assigned to this fault.');
        }

        $fault->update([
            'status'      => 'resolved',
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Fault marked as resolved.');
    }
}