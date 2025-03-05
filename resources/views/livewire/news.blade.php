@section('title', __('News') . ' | Zdrava')
@section('description', __('Zdrava news'))
<div class="container mx-auto px-0 py-12">
    <div class="flex sm:flex-row md:flex-row flex-col gap-4">
        {{-- Первый блок (1/3 экрана) --}}
        <div class="w-full md:w-1/3">
            <div class="w-full mb-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                @if(count($news))
                    @foreach($news as $item)
                        <a href="{{ route('news.view', $item->id) }}">
                            {{ $item->title }}
                        </a>
                    @endforeach
                @else
                    <div class="grid text-sm m-4 grid-cols-1 gap-6 sm:grid-cols-3">
                        Новостей нет
                    </div>
                @endif
            </div>
        </div>
        {{-- Правый контейнер --}}
        <div class="w-full md:w-2/3">
            <div
                class="w-full mb-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div class="flex flex-col p-4 dark:text-white">
                    @if(count($news))
                        @foreach($news as $item)
                            <a href="{{ route('news.view', $item->id) }}">
                                <h3 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ __('Download') }}</h3>
                                <div class="flex flex-col p-4">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        Если ваш стик ANT+ не подключается, возможно необходимо заменить драйвер с помощью программы <a href="https://zadig.akeo.ie/" class="underline underline-offset-2" target="_blank">Zadig</a>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="grid text-sm m-4 grid-cols-1 gap-6 sm:grid-cols-3">
                            Новостей нет :(
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
