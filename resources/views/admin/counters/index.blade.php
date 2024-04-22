<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Counters') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <ul style="
                display: flex;
                flex-wrap:wrap;
                gap: 2rem;">
                @foreach ($counters as $counter)
                    <li>
                        <a href="{{ route('admin.counters.show', $counter->id) }}"
                            style="background: {{ $counter->color }};
                            width: 10rem;
                            height: 10rem;
                            color: #fff;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            border-radius: 0.5rem;">
                            <span> {{ $counter->name }} </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>

