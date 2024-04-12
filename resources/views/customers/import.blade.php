<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Customers') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($errors->any())
                {!! implode('', $errors->all('<div>:message</div>')) !!}
            @endif
            <section>
                <form method="post" action="{{ route('customers.store-import') }}" enctype="multipart/form-data"
                    class="mt-6 space-y-6">
                    @method('POST')
                    @csrf
                    <input type="file" name="file" id="file">
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
