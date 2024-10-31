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
    <div class="grid grid-cols-2 gap-8 px-4 py-6 lg:py-8 md:grid-cols-3">
        <div>
            <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Управление</h2>
            <ul class="text-gray-500 dark:text-gray-400 font-medium">
                <li class="mb-4">
                    <a href="{{ route('admin.users') }}">{{ __('Users') }}</a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('admin.crashlogs') }}">{{ __('Android crash logs') }}</a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('admin.segments') }}">{{ __('Segments') }}</a>
                </li>
            </ul>
        </div>
        <div>
            <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Интеграции</h2>
            <ul class="text-gray-500 dark:text-gray-400 font-medium">
                <li class="mb-4">
                    <a href="{{ route('admin.import.strava.csv') }}">{{ __('Strava import CSV') }}</a>
                </li>
            </ul>
        </div>
        <div>
            <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Внешние сервисы</h2>
            <ul class="text-gray-500 dark:text-gray-400 font-medium">
                <li class="mb-4">
                    <a href="https://console.rustore.ru/apps/2063492362/versions" target="_blank">RuStore Console</a>
                </li>
                <li class="mb-4">
                    <a href="https://search.google.com/search-console?resource_id=sc-domain%3Azdrava.online&hl=ru" target="_blank">Google Search Console</a>
                </li>
                <li class="mb-4">
                    <a href="https://webmaster.yandex.ru/site/https:zdrava.online:443/dashboard/" target="_blank">Yandex Webmaster</a>
                </li>
                <li class="mb-4">
                    <a href="https://smtp.bz/panel/" target="_blank">SMTP.bz</a>
                </li>
                <li class="mb-4">
                    <a href="https://www.svgrepo.com/" target="_blank">Поиск SVG</a>
                </li>
                <li class="mb-4">
                    <a href="https://svgomg.net/" target="_blank">Оптимизация SVG</a>
                </li>
            </ul>
        </div>
    </div>
</main>
