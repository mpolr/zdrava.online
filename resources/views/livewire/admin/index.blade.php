@section('title', __('Admin panel') . ' | Zdrava')
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
            <p><a href="{{ route('admin.users') }}">{{ __('Users') }}</a></p>
            <p><a href="{{ route('admin.segments') }}">{{ __('Segments') }}</a></p>
        </div>
        <div class="w-fit">
            <a href="{{ route('admin.import.strava.csv') }}">{{ __('Strava import CSV') }}</a>
        </div>
        <div class="w-fit">
            <p><a href="https://search.google.com/search-console?resource_id=sc-domain%3Azdrava.online&hl=ru" target="_blank">Google Search Console</a></p>
            <p><a href="https://webmaster.yandex.ru/site/https:zdrava.online:443/dashboard/" target="_blank">Yandex Webmaster</a></p>
        </div>
    </div>
</main>
