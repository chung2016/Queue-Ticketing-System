@extends('layouts.site')

@section('content')
    <div style="display:flex;height:100vh;">
        @foreach ($counters as $counter)
            <section
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
                <button data-counter="{{ $counter->id }}">Get Ticket</button>
            </section>
        @endforeach
        <dialog style="background: #dddddd;
            border-radius: 0.5rem;
            box-shadow: 2px 2px 10px #000000;">
            <form method="POST" action="{{ route('tickets.store') }}" style="padding: 2rem 1rem;">
                @csrf
                <input type="hidden" name="counter_id" id="counter_id" value="321">
                <div
                    style="display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 2rem;
                    margin-bottom: 1rem;">
                    <span>
                        name:
                    </span>
                    <input type="text" name="name" id="name">
                </div>
                <div
                    style="display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 2rem;">
                    <button type="submit" style="border: 1px solid #555;
                    padding: 5px 15px;
                    border-radius: 0.5rem;">Submit</button>
                    <button type="button" id="close" style="border: 1px solid #555;
                    padding: 5px 15px;
                    border-radius: 0.5rem;">Cancel</button>
                </div>
            </form>
        </dialog>
    </div>
@endsection

@section('js')
    <script>
        document.querySelectorAll('button').forEach(getTicketBtn => {
            getTicketBtn.addEventListener('click', function(e) {
                document.querySelector('dialog').showModal();
                document.getElementById('counter_id').value = getTicketBtn.getAttribute('data-counter')
                    .toString();
            });
        });
        document.getElementById('close').addEventListener('click', function(e) {
            document.querySelector('dialog').close();
            document.querySelector('form').reset();
        });
    </script>
    @if ($errors->any())
        <script>
            alert("{!! implode('', $errors->all(':message')) !!}")
        </script>
    @endif
@endsection
