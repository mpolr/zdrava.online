@section('title', __('ANT+') . ' | Zdrava')
@section('js')
    @vite('resources/js/ant.js')
@endsection
<div class="container mx-auto px-0 py-12">
    <div class="flex sm:flex-row md:flex-row flex-col gap-4">
        {{-- Первый блок (1/3 экрана) --}}
        <div class="w-full md:w-1/3">
            <div class="w-full">
                <div class="p-6 bg-white dark:bg-gray-200 rounded-xl shadow-md space-y-4">
                    <h2 class="text-xl font-bold text-gray-900">Тест датчиков ANT+</h2>
                    <p class="text-gray-700">Стик ANT+ USB:
                        <strong id="stick-status" class="text-red-600">Отключен</strong>
                    </p>

                    <div id="heart-rate-display" class="hidden">
                        <strong>{{ __('HRM') }}:</strong>
                        <span id="heart-rate-status" class="text-red-600">Отключен</span>
                        <span id="heart-rate-value">-- bpm</span>
                    </div>
                    <div id="cadence-display" class="hidden">
                        <strong>{{ __('Cadence') }}:</strong>
                        <span id="cadence-status" class="text-red-600">Отключен</span>
                        <span id="cadence-value">-- rpm</span>
                    </div>
                    <div id="power-display" class="hidden">
                        <strong>{{ __('Power meter') }}:</strong>
                        <span id="power-status" class="text-red-600">Отключен</span>
                        <span id="power-value">-- W</span>
                    </div>

                    <div class="space-y-2">
                        <button id="connect" class="w-full px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 focus:ring focus:ring-green-300 focus:outline-none">
                            {{ __('Connect') }}
                        </button>
                        <button id="disconnect" class="w-full px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 focus:ring focus:ring-red-300 focus:outline-none">
                            {{ __('Disconnect') }}
                        </button>
                    </div>

                    @if (session()->has('message'))
                        <p class="text-green-500">{{ session('message') }}</p>
                    @endif
                    @if (session()->has('error'))
                        <p class="text-red-500">{{ session('error') }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div>
            <div class="p-6 bg-white dark:bg-gray-200 rounded-xl shadow-md space-y-4">
                <div class="dark:bg-gray-200">
                    <p>
                        Если у вас есть ANT+ USB стик (адаптер) то вы можете протестировать его и свои датчики ANT+ на этой странице.
                    </p>
                    <p class="pt-6">
                        После нажатия на кнопку "Подключить" сайт запросит разрешение на подключение к вашему стику ANT+ USB. Обратите внимание,
                        этот функционал доступен не во всех браузерах (не работает в Firefox).
                    </p>
                    <p class="pt-6">
                        В случае успешного подключения к стику, будет произведён поиск датчиков (пульсометры, датчики каденса, измерители мощности).
                        Если датчики найдены и подключены - в колонке слева будут отображаться данные с этих датчиков.
                    </p>
                </div>
            </div>
        </div>
        {{-- Правый контейнер --}}
        <div class="w-full md:w-1/3">

                <div
                    class="w-full mb-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex flex-col p-4">
                        <h3 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ __('Download') }}</h3>
                        <div class="flex flex-col p-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Если ваш стик ANT+ не подключается, возможно необходимо заменить драйвер с помощью программы <a href="https://zadig.akeo.ie/" target="_blank">Zadig</a>
                            </span>
                        </div>
                    </div>
                </div>

        </div>
    </div>
</div>
