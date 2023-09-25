<main class="container mx-auto px-0 py-12 max-w-screen-lg">
    <div class="grid grid-cols-1 gap-4">
        <div class="w-full">
            @error('admin') @livewire('toast.errors') @enderror
            @if (Session::get('success'))
                @livewire('toast.success')
            @endif
        </div>
    </div>
    <div class="grid grid-cols-3 gap-4">
        <div class="w-full">
            <a href="{{ route('admin.users') }}">{{ __('Users') }}</a>
        </div>
        <div class="w-fit">
            <a href="{{ route('admin.import.strava.csv') }}">{{ __('Strava import CSV') }}</a>
        </div>
        <div class="w-fit">

        </div>
    </div>
</main>
