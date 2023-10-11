@section('title', __('Find friends') . ' | Zdrava')
<main class="container mx-auto px-0 pt-12 max-w-screen-2xl">
    <div class="grid grid-cols-1 gap-4">
        <div class="w-full">
            <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black dark:text-gray-100">
                {{ __('Find friends') }}
            </h2>
            <hr class="h-px mt-4 mb-8 bg-gray-200 border-0 dark:bg-gray-700">
            @error('search') @livewire('toast.errors') @enderror
            @if (Session::get('success'))
                @livewire('toast.success')
            @endif
            <div>
                <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">{{ __('Search') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input wire:model="search" type="search" id="search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="{{ __('Name, nickname or email...') }}" required>
                    <button wire:click="search" type="button" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        {{ __('Search') }}
                    </button>
                </div>
                <div>
                    @if(empty($search))
                        <h4 class="text-2xl mt-4 font-bold dark:text-gray-100">{{ __('Before you start your search, you may know these people') }}</h4>
                    @endif
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            @if(Auth::user()->id !== $user->id)
                                <li class="flex justify-between gap-x-6 py-5">
                                    <div class="flex gap-x-4">
                                        @if($user->getPhoto())
                                            <a href="{{ route('athlete.profile', $user->id) }}">
                                                <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="{{ $request->user->getPhoto() }}" alt="">
                                            </a>
                                        @else
                                            <a href="{{ route('athlete.profile', $user->id) }}">
                                                <div class="relative inline-flex items-center justify-center w-12 h-12 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                                            <span class="font-medium text-gray-600 dark:text-gray-300">
                                                {{ $user->getInitials() }}
                                            </span>
                                                </div>
                                            </a>
                                        @endif
                                        <div class="min-w-0 flex-auto">
                                            <p class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                                                <a href="{{ route('athlete.profile', $user->id) }}">
                                                    {{ $user->getFullName() }}
                                                </a>
                                            </p>
                                            <p class="mt-1 truncate text-xs leading-5 text-gray-500">
                                                <a href="{{ route('athlete.profile', $user->id) }}">
                                                    {{ $user->getNickname(true) }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="hidden sm:flex sm:flex-col sm:items-end">
                                        @if(!in_array($user->id, $subscriptions))
                                            @if (!in_array($user->id, $awaiting))
                                                @if (!$user->private)
                                                    <button wire:click="subscribe({{ $user }})" type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                        {{ __('Subscribe') }}
                                                    </button>
                                                @else
                                                    <button wire:click="subscribe({{ $user }})" type="button" class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900">
                                                        {{ __('Subscription request') }}
                                                    </button>
                                                @endif
                                            @else
                                                <button wire:click="cancel({{ $user }})" type="button" class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900">
                                                    {{ __('Cancel subscription request') }}
                                                </button>
                                            @endif
                                        @else
                                            <button wire:click="unsubscribe({{ $user }})" type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                {{ __('Unsubscribe') }}
                                            </button>
                                        @endif
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
