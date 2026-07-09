<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        Ticket::where('user_id', $user->id)
              ->where('status', 'active')
              ->where('valid_until', '<', today())
              ->update(['status' => 'expired']);

        $tickets = Ticket::where('user_id', $user->id)
                         ->orderBy('created_at', 'desc')
                         ->get();

        $activeTickets  = $tickets->where('status', 'active');
        $expiredTickets = $tickets->where('status', 'expired');

        return view('passenger.tickets', compact('tickets', 'activeTickets', 'expiredTickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:single,day,weekly,monthly',
            'zone' => 'required|in:A,B,A+B',
        ]);

        $prices = [
            'single'  => ['A' => 89,   'B' => 89,   'A+B' => 139],
            'day'     => ['A' => 250,  'B' => 250,  'A+B' => 390],
            'weekly'  => ['A' => 800,  'B' => 800,  'A+B' => 1200],
            'monthly' => ['A' => 2400, 'B' => 2400, 'A+B' => 3600],
        ];

        $validDays = [
            'single'  => 1,
            'day'     => 1,
            'weekly'  => 7,
            'monthly' => 30,
        ];

        Ticket::create([
            'user_id'     => auth()->id(),
            'type'        => $request->type,
            'zone'        => $request->zone,
            'price'       => $prices[$request->type][$request->zone],
            'valid_from'  => today(),
            'valid_until' => today()->addDays($validDays[$request->type]),
            'status'      => 'active',
            'qr_code'     => 'TICKET-' . strtoupper(Str::random(10)),
        ]);

        return back()->with('success', 'Ticket purchased successfully!');
    }
}