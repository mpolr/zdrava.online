@section('title', __('News') . ' | Zdrava')
@section('description', __('Zdrava news'))
<div class="container mx-auto px-0 py-12">
    <div class="flex sm:flex-row md:flex-row flex-col gap-4">
        {{-- Правый контейнер --}}
        <div class="w-full">
            <div
                class="w-full mb-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div class="flex flex-col p-4 dark:text-white">
                    <h4 class="mb-2 mt-0 text-3xl font-medium leading-tight text-black dark:text-gray-100">
                        <a href="{{ route('admin.news') }}">
                            <svg style="display:inline;vertical-align:text-top;" class="w-8 h-8" viewBox="0 0 200 200" data-name="Layer 1">
                                <path d="M100 15a85 85 0 1 0 85 85 84.93 84.93 0 0 0-85-85Zm0 150a65 65 0 1 1 65-65 64.87 64.87 0 0 1-65 65Zm16.5-107.5a9.67 9.67 0 0 0-14 0L74 86a19.92 19.92 0 0 0 0 28.5l28.5 28.5a9.9 9.9 0 0 0 14-14l-28-29L117 71.5c3.5-3.5 3.5-10-.5-14Z"/>
                            </svg>
                        </a>
                        {{ __('News') }}
                    </h4>

                    <div class="mb-6">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Title') }}</label>
                        <input wire:model="news.title" value="{{ $news->title }}" type="text" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" required>
                    </div>

                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $news->created_at }}</span>
                    @if($news !== null)
                        <x-ckeditor :model="$news->content" route="admin.news.edit" attr="news.content" />
                    @else
                        <x-ckeditor content="" :model="$news->content" route="admin.news.edit" attr="news.content" />
                    @endif

                    <div class="mb-6">
                        <label for="published" class="flex items-center mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            <input
                                type="checkbox"
                                id="published"
                                wire:model="news.published"
                                class="mr-2 focus:ring-indigo-500 h-4 w-4 rounded border-gray-300 text-indigo-600"
                            />
                            {{ __('Published') }}
                        </label>

                    </div>

                    <button wire:click="save" type="button" class="w-fit object-right px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-full">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
