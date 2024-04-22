<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminCounterController extends Controller
{
    public function index()
    {
        $counters = Counter::all();
        return view('admin.counters.index', compact('counters'));
    }

    public function show(Counter $counter)
    {
        return view('admin.counters.show', compact('counter'));
    }

    public function queue(Counter $counter)
    {
        $queue = $counter
            ->tickets()
            ->with('customer')
            ->whereIn('status', ['open', 'processing'])
            ->get()
            ->map(function ($ticket) {
                $ticket->number = $ticket->counter->name . str_pad($ticket->number, 3, '0', STR_PAD_LEFT);
                return $ticket;
            });
        return response()->json($queue);
    }

    public function actionTicket(Request $request)
    {
        $status = '';
        $action = $request->get('action');
        if ($action === 'pick') {
            $status = 'processing';
        } else if ($action === 'close') {
            $status = 'closed';
        } else if ($action === 'cancel') {
            $status = 'canceled';
        }
        $ticket_id = $request->get('ticket_id');
        $ticket = Ticket::query()
            ->where('id', $ticket_id)
            ->first();
        if ($ticket) {
            $ticket->status = $status;
            $ticket->save();
        }
        return response()->json(['ticket_id' => $ticket_id]);
    }
}
