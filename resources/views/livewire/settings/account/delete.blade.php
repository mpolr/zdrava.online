@section('title', __('My account') . ' | Zdrava')
<main class="container mx-auto px-0 py-12 max-w-screen-lg">
    <div class="grid grid-cols-3 gap-4">
        <div class="w-full">
            @include('components.settings-menu')
        </div>
        <div class="w-full col-span-2">
            <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black dark:text-gray-100">
                {{ __('Account deleting') }}
            </h2>
            <hr class="h-px mt-4 mb-8 bg-gray-200 border-0 dark:bg-gray-700">
            @error('profile') @livewire('toast.errors') @enderror
            @if (session()->get('success'))
                @livewire('toast.success')
            @endif
            <div class="block mt-8 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Что это значит
                </h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">
                    На этой странице вы можете удалить свой аккаунт. Отменить удаление аккаунта невозможно.
                    Ваш аккаунт и его данные (в том числе с карты активности, из задач и рейтингов) будут удалены навсегда и вы будете исключены из всех клубов.
                    В Zdrava может сохраниться созданный вами контент, например, информация по открытым участкам или маршрутам.
                </p>
            </div>
            <div class="block mt-8 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Запрос на удаление
                </h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">
                    Чтобы никто, кроме вас, не мог удалить ваш аккаунт, необходимо подтвердить запрос по электронной почте.
                    Мы отправим уведомление о последнем этапе по адресу <strong>{{ auth()->user()->email }}</strong>.
                    <br><br>
                    Ваш аккаунт не будет удален, пока вы не выполните инструкции из письма.
                </p>
                <div class="flex items-center p-4 my-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        Аккаунт и все данные удаляются навсегда. Их нельзя будет скачать или восстановить.
                    </div>
                </div>
            </div>
            <p class="pt-4">
                <button wire:click="delete" type="button" class="px-4 py-2 bg-red-500 hover:bg-red-700 text-white font-bold rounded-full">{{ __('Account deletion request') }}</button>
            </p>
        </div>
    </div>
</main>
