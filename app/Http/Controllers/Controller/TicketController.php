<?php

namespace App\Http\Controllers\Controller;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user')
                         ->orderBy('created_at', 'desc')
                         ->get();

        $activeCount    = $tickets->where('status', 'active')->count();
        $expiredCount   = $tickets->where('status', 'expired')->count();
        $cancelledCount = $tickets->where('status', 'cancelled')->count();

        return view('controller.controllerscan', compact(
            'tickets',
            'activeCount',
            'expiredCount',
            'cancelledCount'
        ));
    }

    public function validate(Request $request)
{
    $request->validate([
        'qr_code' => 'required|string',
    ]);

    $ticket = Ticket::with('user')
                    ->where('qr_code', $request->qr_code)
                    ->first();

    if (!$ticket) {
        return back()->with('scan_result', [
            'status'  => 'invalid',
            'message' => 'Ticket not found.',
            'reason'  => 'not_found',
        ]);
    }

    if ($ticket->status === 'cancelled') {
        return back()->with('scan_result', [
            'status'  => 'invalid',
            'message' => 'Ticket has been cancelled.',
            'reason'  => 'cancelled',
            'ticket'  => $ticket,
        ]);
    }

    if ($ticket->status === 'expired') {
        return back()->with('scan_result', [
            'status'  => 'invalid',
            'message' => 'Ticket has already been used or expired.',
            'reason'  => 'expired',
            'ticket'  => $ticket,
        ]);
    }

    if ($ticket->valid_until < today()) {
        $ticket->update(['status' => 'expired']);
        return back()->with('scan_result', [
            'status'  => 'invalid',
            'message' => 'Ticket has expired.',
            'reason'  => 'expired',
            'ticket'  => $ticket,
        ]);
    }

    if ($ticket->type === 'single') {
        $ticket->update(['status' => 'expired']);
    }

    return back()->with('scan_result', [
        'status'  => 'valid',
        'message' => $ticket->type === 'single'
            ? 'Valid — single journey used. Ticket is now expired.'
            : 'Ticket is valid.',
        'ticket'  => $ticket,
    ]);
}
}