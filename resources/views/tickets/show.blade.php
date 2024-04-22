@extends('layouts.site')

@section('content')
    <div style="display:flex;height:100vh;">
        <section
            style="flex: 1;
            display: flex;
            flex-direction: column;
            text-align: center;
            height: 100%;
            justify-content: center;
            align-items: center;
            background: {{ $ticket->counter->color }};
            color:#ffffff;
            text-shadow: 1px 1px 7px #757575;">
            <div>
                <span>Server time: </span>
                <span id="server-time"></span>
            </div>
            <div style="font-size:2rem;">
                Your number is
            </div>
            <div style="font-size:4rem;">
                {{ $ticket->counter->name . str_pad($ticket->number, 3, '0', STR_PAD_LEFT) }}
            </div>
            <div style="font-size:2rem;">
                <span>
                    From:
                </span>
                <span id="from"></span>
            </div>
            <div style="font-size:2rem;">
                <span>
                    Current:
                </span>
                <span id="current-ticket"></span>
            </div>
            <form method="post" action="{{ route('tickets.destroy', $ticket) }}" class="p-6 text-center">
                @csrf
                @method('delete')
                <x-danger-button class="ms-3" onclick="return confirm('Are you sure?')">
                    {{ __('Leave') }}
                </x-danger-button>
            </form>
        </section>
    </div>
    <dialog
        style="background: #dddddd;
    border-radius: 0.5rem;
    box-shadow: 2px 2px 10px #000000;
    text-align:center;
    padding: 2rem 1rem;">
        <div>Your ticket has been ended</div>
        <button onclick="leave()"
            style="border: 1px solid #555;
                    padding: 5px 15px;
                    border-radius: 0.5rem;">Leave</button>
    </dialog>
@endsection

@section('js')
    @if (session('message') === 'has_current')
        <script>
            alert('you has current ticket')
        </script>
    @endif
    <script>
        const counterId = {{ $ticket->counter_id }}
        const serverTime = {{ now()->timestamp * 1000 }};
        const localTime = Date.now();
        const serverLocalDiff = serverTime - localTime;

        async function getCurrentTicket() {
            try {
                const result = await fetch("{{ route('api.ticket.show', $ticket->id) }}", {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });
                const data = await result.json();
                if (data.status === 'closed' || data.status === 'canceled') {
                    document.querySelector('dialog').showModal();

                }
            } catch (error) {
                console.error(error);
            } finally {
                setTimeout(getCurrentTicket, 2000);
            }
        }

        function leave() {
            window.location.href = "{{ route('tickets.index') }}"
        }

        getCurrentTicket();

        setInterval(() => {
            document.getElementById('server-time').innerHTML = dateInYyyyMmDdHhMmSs(new Date(Date.now() +
                serverLocalDiff));
        }, 1000);
        document.getElementById('from').innerHTML = dateInYyyyMmDdHhMmSs(new Date({{ $ticket->created_at->timestamp }} *
            1000));

        async function getCounterServing() {
            const result = await fetch("{{ route('counters.serving') }}")
            const data = await result.json()
            document.getElementById('current-ticket').innerHTML = data[counterId].tickets ?
                data[counterId].tickets :
                '-';
            setTimeout(getCounterServing, 5000);
        }
        getCounterServing();
    </script>
@endsection
