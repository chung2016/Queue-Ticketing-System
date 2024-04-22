<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Counter;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index()
    {
        $counters = Counter::all();
        return view('tickets.index', compact('counters'));
    }

    public function store(StoreTicketRequest $request)
    {
        $customer_id = Customer::query()
            ->where('name', $request->name)
            ->first()
            ->id;
        $counter_id = $request->counter_id;
        $ticket = Ticket::query()
            ->where('customer_id', $customer_id)
            ->where('status', 'open')
            ->first();

        if ($ticket) {
            return redirect()
                ->route('tickets.show', [$ticket])
                ->with('message', 'has_current');
        }

        $ticket = DB::transaction(function () use ($counter_id, $customer_id) {
            $counter = Counter::query()
                ->where('id', $counter_id)
                ->first();

            return Ticket::create([
                'number' => $counter->next_ticket_number,
                'customer_id' => $customer_id,
                'counter_id' => $counter_id,
            ]);
        });
        return redirect()
            ->route('tickets.show', [$ticket]);
    }

    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    public function apiShow(Ticket $ticket)
    {
        return response()->json($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->update(['status' => 'canceled']);
        return redirect()->route('tickets.index');
    }
}
