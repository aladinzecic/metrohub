<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Fault;

class InspectionController extends Controller
{
    public function index()
    {
        $mechanic = auth()->user();

        $myFaults = Fault::with(['vehicle', 'reporter'])
                         ->where('mechanic_id', $mechanic->id)
                         ->whereIn('status', ['open', 'in_progress'])
                         ->orderByRaw("CASE severity WHEN 'critical' THEN 1 WHEN 'moderate' THEN 2 WHEN 'minor' THEN 3 END")
                         ->get();

        $completedFaults = Fault::with(['vehicle', 'reporter'])
                                ->where('mechanic_id', $mechanic->id)
                                ->where('status', 'resolved')
                                ->orderBy('resolved_at', 'desc')
                                ->take(10)
                                ->get();

        return view('mechanic.mechanicinspections', compact('myFaults', 'completedFaults'));
    }
}