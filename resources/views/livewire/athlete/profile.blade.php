@section('title', $user->getFullName() . ' | Zdrava')
<main class="container mx-auto px-0 pt-12 max-w-screen-2xl">
    @error('profile') @livewire('toast.errors') @enderror
    @if (Session::get('success'))
        @livewire('toast.success')
    @endif
    <div class="grid grid-cols-4 gap-4">
        <div class="w-full">
            <div class="flex items-center justify-center w-full">
                @if ($user->getPhoto())
                    <img src="{{ $user->getPhoto() }}" alt="{{ $user->getFullName() }}" class="w-32 h-32 mb-3 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" loading="lazy"/>
                @else
                    <div class="relative inline-flex items-center justify-center w-24 h-24 mb-3 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                        <span class="font-bold text-3xl text-gray-600 dark:text-gray-300">
                            {{ $user->getInitials() }}
                        </span>
                    </div>
                @endif
                <h2 class="mb-2 mt-0 ml-4 text-4xl font-medium leading-tight text-black dark:text-gray-100">
                    {{ $user->getFullName() }}
                </h2>
            </div>
            <hr class="h-px mt-4 mb-8 bg-gray-200 border-0 dark:bg-gray-700">
            <div class="grid text-sm mt-4 grid-cols-3 gap-6 sm:grid-cols-3">
                <a href="{{ route('athlete.subscriptions.user', $user->id) }}">
                    <p class="mb-3 text-center text-gray-600 dark:text-gray-400">
                        {{ __('Subscriptions') }}<br />{{ $user->subscriptions()->count() }}
                    </p>
                </a>
                <a href="{{ route('athlete.subscribers.user', $user->id) }}">
                    <p class="mb-3 text-center text-gray-600 dark:text-gray-400">
                        {{ __('Subscribers') }}<br />{{ $user->subscribers()->where('confirmed', 1)->count() }}
                    </p>
                </a>
                <a href="{{ route('athlete.training') }}">
                    <p class="mb-3 text-center text-gray-600 dark:text-gray-400">
                        {{ __('Activities') }}<br />
                        {{ $user->activities->count() ? $user->activities->count() : 0 }}
                    </p>
                </a>
            </div>
        </div>
        <div class="w-full col-span-2">
            <div class="pl-4">
                @error('profile') @livewire('toast.errors') @enderror
                @if (Session::get('success'))
                    @livewire('toast.success')
                @endif
                @if(count($user->activities) >= 1)
                    @foreach($user->activities as $activity)
                        <div class="max-w-full mb-3 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <div class="p-4">
                                <div class="flex gap-x-4">
                                    <a href="{{ route('athlete.profile', $activity->getUser()->id) }}">
                                    @if($activity->getUser()->getPhoto())
                                        <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="{{ $activity->getUser()->getPhoto() }}" alt="{{ $activity->getUser()->getFullName() }}">
                                    @else
                                        <div class="relative inline-flex items-center justify-center w-12 h-12 mb-3 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                                        <span class="font-bold text-xl text-gray-600 dark:text-gray-300">
                                            {{ $activity->getUser()->getInitials() }}
                                        </span>
                                        </div>
                                    @endif
                                    </a>
                                    <div class="min-w-0 flex-auto">
                                        <a href="{{ route('athlete.profile', $activity->getUser()->id) }}">
                                            <p class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">{{ $activity->getUser()->getFullName() }}</p>
                                        </a>
                                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">
                                            {{ $activity->getLongStartDate() }} - {{ $activity->getCountry() }}@if($activity->locality), {{ $activity->locality }}@endif
                                        </p>
                                        <a href="{{ route('activities.get', $activity->id) }}">
                                            <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                                {{ $activity->name }}
                                            </h5>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ Str::limit($activity->description, 140, ' ...') }}
                                            </p>
                                        </a>
                                        <div class="grid text-sm mt-4 grid-cols-1 gap-6 sm:grid-cols-3">
                                            <p class="mb-3 text-left text-gray-600 dark:text-gray-400">
                                                {{ __('Distance') }}<br />
                                                <span class="text-lg font-semibold">{{ __(':distance km', ['distance' => $activity->getDistance()]) }}</span>
                                            </p>
                                            <p class="mb-3 text-left text-gray-600 dark:text-gray-400">
                                                {{ __('Elevation') }}<br />
                                                <span class="text-lg font-semibold">{{ __(':elevation m', ['elevation' => $activity->elevation_gain]) }}</span>
                                            </p>
                                            <p class="mb-3 text-left text-gray-600 dark:text-gray-400">
                                                {{ __('Duration') }}<br />
                                                <span class="text-lg font-semibold">{{ $activity->getDuration() }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-0">
                                <a href="{{ route('activities.get', $activity->id) }}">
                                    <img class="h-auto max-w-full" src="{{ $activity->getImage() }}" alt="" />
                                </a>
                            </div>
                            <div class="p-0">
                                <div class="flex flex-wrap items-center justify-end gap-4" role="group">
                                    <livewire:components.like :model="$activity" />
                                    <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-transparent hover:text-black focus:text-orange-500 dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>
                                        {{ count($activity->comments) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Боковое меню справа -->
                    <div class="w-full mb-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex flex-col p-4">
                            <div class="flex flex-col p-4">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('This athlete has no training yet') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="w-full">

        </div>
    </div>
</main>
