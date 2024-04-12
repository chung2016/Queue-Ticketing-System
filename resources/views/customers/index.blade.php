<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customers') }}
        </h2>
        <x-nav-link href="{{ route('customers.create') }}">
            {{ __('Create') }}
        </x-nav-link>
        <x-nav-link href="{{ route('customers.import') }}">
            {{ __('Import CSV') }}
        </x-nav-link>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section>
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>
                                    <div class="p-6 text-center">{{ $customer->name }}</div>
                                </td>
                                <td>
                                    <form method="post" action="{{ route('customers.destroy', $customer) }}"
                                        class="p-6 text-center">
                                        @csrf
                                        @method('delete')
                                        <x-nav-link href="{{ route('customers.edit', $customer) }}">
                                            {{ __('Edit') }}
                                        </x-nav-link>
                                        <x-danger-button class="ms-3" onclick="return confirm('Are you sure?')">
                                            {{ __('Delete') }}
                                        </x-danger-button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $customers->links() }}
            </section>
        </div>
    </div>
</x-app-layout>
