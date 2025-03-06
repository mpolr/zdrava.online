@section('title', __('News') . ' | Zdrava')
@section('description', __('Zdrava news'))
<div class="container mx-auto px-0 py-12">
    <div class="flex sm:flex-row md:flex-row flex-col gap-4">
        {{-- Первый блок (1/3 экрана) --}}
        <div class="w-full md:w-1/3">
            <div class="w-full mb-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                @if(count($news))
                    @foreach($news as $item)
                        <div class="grid text-sm m-4 grid-cols-1 gap-6 sm:grid-cols-3">
                            <a href="{{ route('news.view', $item->id) }}">
                                {{ $item->title }}
                            </a>
                        </div>
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
                                <h3 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $item->title }}</h3>
                            </a>
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $item->created_at }}</span>
                            <div class="flex flex-col p-4 py-6">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->content }}
                                </span>
                            </div>
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
