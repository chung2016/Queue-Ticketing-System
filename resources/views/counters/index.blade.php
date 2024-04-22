@extends('layouts.site')

@section('content')
    <div style="display:flex;height:100vh;">
        @foreach ($counters as $counter)
            <section data-counter-id="{{ $counter->id }}"
                style="flex: 1;
            display: flex;
            flex-direction: column;
            text-align: center;
            height: 100%;
            justify-content: center;
            align-items: center;
            background: {{ $counter->color }};
            color:#ffffff;
            text-shadow: 1px 1px 7px #757575;">
                <div style="font-size:4rem;">
                    Counter {{ $counter->name }}
                </div>
                <div style="font-size:2rem;">
                    <span>
                        Current:
                    </span>
                    <span class="current-ticket"></span>
                </div>
                <div style="font-size:2rem;">
                    <span>
                        Start Time:
                    </span>
                    <span class="ticket-start-time"></span>
                </div>
            </section>
        @endforeach
    </div>
@endsection

@section('js')
    <script>
        async function getCounterServing() {
            try {
                const result = await fetch("{{ route('counters.serving') }}")
                const data = await result.json()
                const counters = Object.values(data)
                counters.forEach(counter => {
                    document.querySelector(`[data-counter-id='${counter['counter_id']}'] .current-ticket`)
                        .innerHTML = counter['tickets'] ? counter['tickets'] : '-';
                    document.querySelector(`[data-counter-id='${counter['counter_id']}'] .ticket-start-time`)
                        .innerHTML = counter['updated_at'] ? dateInYyyyMmDdHhMmSs(new Date(counter[
                            'updated_at'] * 1000)) : '-';
                });
            } catch (error) {
                console.error(error)
            } finally {
                setTimeout(getCounterServing, 1000);
            }
        }
        getCounterServing();
    </script>
@endsection
