<main class="container mx-auto px-0 py-12 max-w-screen-2xl">
    <div class="grid grid-cols-1 gap-4">
        <div class="w-full">
            @error('admin') @livewire('toast.errors') @enderror
            @if (Session::get('success'))
                @livewire('toast.success')
            @endif
        </div>
    </div>
    <div class="grid grid-cols-1 gap-4">
        <div class="w-full">
            <!-- Контент -->
            <h4 class="mb-2 mt-0 text-3xl font-medium leading-tight text-black dark:text-gray-100">
                <a href="{{ route('admin.index') }}">
                    <svg style="display:inline;vertical-align:text-top;" class="w-8 h-8" viewBox="0 0 200 200" data-name="Layer 1">
                        <path d="M100 15a85 85 0 1 0 85 85 84.93 84.93 0 0 0-85-85Zm0 150a65 65 0 1 1 65-65 64.87 64.87 0 0 1-65 65Zm16.5-107.5a9.67 9.67 0 0 0-14 0L74 86a19.92 19.92 0 0 0 0 28.5l28.5 28.5a9.9 9.9 0 0 0 14-14l-28-29L117 71.5c3.5-3.5 3.5-10-.5-14Z"/>
                    </svg>
                </a>
                {{ __('Users') }}: {{ __(':count from :total', ['count' => $users->firstItem() . '-' . $users->lastItem(), 'total' => $users->total()]) }}
            </h4>
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
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            {{ __('ID') }}
                        </th>
                        <th scope="col" class="px-2 py-3">
                            <div class="flex items-center">
                                {{ __('Avatar') }}
                            </div>
                        </th>
                        <th scope="col" class="px-2 py-3">
                            <div class="flex items-center">
                                {{ __('Name') }}
                                <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                            </div>
                        </th>
                        <th scope="col" class="px-2 py-3">
                            <div class="flex items-center">
                                {{ __('Nickname') }}
                                <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                            </div>
                        </th>
                        <th scope="col" class="px-2 py-3">
                            <div class="flex items-center">
                                {{ __('Email') }}
                                <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                            </div>
                        </th>
                        <th scope="col" class="px-2 py-3">
                            <div class="flex items-center">
                                {{ __('Workout') }}
                                <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                            </div>
                        </th>
                        <th scope="col" class="px-2 py-3">
                            <div class="flex items-center">
                                {{ __('Subscriptions') }}
                                <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                            </div>
                        </th>
                        <th scope="col" class="px-2 py-3">
                            <div class="flex items-center">
                                {{ __('Subscribers') }}
                                <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                            </div>
                        </th>
                        <th scope="col" class="px-2 py-3">
                            <div class="flex items-center">
                                {{ __('Private profile') }}
                                <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                            </div>
                        </th>
                        <th scope="col" class="px-2 py-3">
                            <div class="flex items-center">
                                {{ __('Created') }}
                                <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($users))
                        @foreach($users as $user)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $user->id }}
                                </th>
                                <td class="px-2 py-4">
                                    @if($user->getPhoto())
                                        <img class="h-12 w-12 flex-none rounded-full" src="{{ $user->getPhoto() }}" alt="">
                                    @else
                                        <div class="relative inline-flex items-center justify-center w-12 h-12 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                                                <span class="font-medium text-gray-600 dark:text-gray-300">
                                                    {{ $user->getInitials() }}
                                                </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-2 py-4">
                                    <a href="{{ route('athlete.profile', $user->id) }}" target="_blank">
                                        {{ $user->getFullName() }}
                                    </a>
                                </td>
                                <td class="px-2 py-4">
                                    {{ $user->nickname }}
                                </td>
                                <td class="px-2 py-4">
                                    @if($user->email_verified_at)
                                        <span class="font-medium text-green-600 dark:text-green-300">
                                            {{ $user->email }}
                                        </span>
                                    @else
                                        <span class="font-medium text-red-600 dark:text-red-300">
                                            {{ $user->email }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-2 py-4">
                                    {{ $user->activities->count() }}
                                </td>
                                <td class="px-2 py-4">
                                    {{ $user->confirmedSubscriptions()->count() }} ({{ $user->subscriptions->count() }})
                                </td>
                                <td class="px-2 py-4">
                                    {{ $user->confirmedSubscribers()->count() }} ({{ $user->subscribers->count() }})
                                </td>
                                <td class="px-2 py-4">
                                    @if($user->private)
                                        {{ __('Yes') }}
                                    @else
                                        {{ __('No') }}
                                    @endif
                                </td>
                                <td class="px-2 py-4">
                                    {{ $user->created_at }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</main>
