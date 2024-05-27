@section('title', __('Zdrava account deleting') . ' | Zdrava')
<main class="container mx-auto px-0 py-12 max-w-screen-lg">
    <div class="grid grid-cols-3 gap-4">
        @auth()
        <div class="w-full">
            @include('components.settings-menu')
        </div>
        @endauth
        <div class="w-full col-span-2">
            <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black dark:text-gray-100">
                {{ __('Zdrava account deleting') }}
            </h2>
            <hr class="h-px mt-4 mb-8 bg-gray-200 border-0 dark:bg-gray-700">
            @error('profile') @livewire('toast.errors') @enderror
            @if (session()->get('success'))
                @livewire('toast.success')
            @endif
            <div class="block mt-8 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ __('What does it mean') }}
                </h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">
                    {{ __('On this page you can delete your account. It is not possible to undo account deletion. Your account and its data (including from the activity map, tasks and ratings) will be deleted forever and you will be excluded from all clubs. Zdrava may store content you create, such as information about open areas or routes.') }}
                </p>
            </div>
            <div class="block mt-8 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ __('Removal request') }}
                </h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">
                    {{ __('To prevent anyone other than you from deleting your account, you must confirm your request by email. We will send notification of the final step to') }}
                    @auth() <strong>{{ auth()->user()->email }}</strong>. @endauth
                    @guest() email @endguest
                    <br><br>
                    {{ __('Your account will not be deleted until you follow the instructions in the email.') }}
                </p>
                <div class="flex items-center p-4 my-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        {{ __('The account and all data are deleted forever. They cannot be downloaded or restored.') }}
                    </div>
                </div>
            </div>
            <p class="pt-4">
                @auth()
                    <button wire:click="delete" type="button" class="px-4 py-2 bg-red-500 hover:bg-red-700 text-white font-bold rounded-full">{{ __('Request account deletion') }}</button>
                @endauth
                @guest()
                    <a href="{{ route('settings.account.delete') }}" type="button" class="px-4 py-2 bg-red-500 hover:bg-red-700 text-white font-bold rounded-full">{{ __('Login') }} & {{ __('Request account deletion') }}</a>
                @endguest
            </p>
        </div>
    </div>
</main>
