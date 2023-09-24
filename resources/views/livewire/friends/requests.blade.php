<main class="container mx-auto px-0 pt-12 max-w-screen-2xl">
    <div class="grid grid-cols-1 gap-4">
        <div class="w-full">
            <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black">
                {{ __('Subscription requests') }}
            </h2>
            <hr class="h-px mt-4 mb-8 bg-gray-200 border-0 dark:bg-gray-700">
            @error('search') @livewire('toast.errors') @enderror
            @if (Session::get('success'))
                @livewire('toast.success')
            @endif
            <div>
                <div>
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($requests as $request)
                            <li class="flex justify-between gap-x-6 py-5">
                                <div class="flex gap-x-4">
                                    @if($request->user->photo)
                                        <a href="{{ route('athlete.profile', $request->user->id) }}">
                                            <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="{{ $request->user->getPhoto() }}" alt="">
                                        </a>
                                    @else
                                        <a href="{{ route('athlete.profile', $request->user->id) }}">
                                            <div class="relative inline-flex items-center justify-center w-12 h-12 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                                                <span class="font-medium text-gray-600 dark:text-gray-300">
                                                    {{ $request->user->getInitials() }}
                                                </span>
                                            </div>
                                        </a>
                                    @endif
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-sm font-semibold leading-6 text-gray-900">
                                            <a href="{{ route('athlete.profile', $request->user->id) }}">
                                                {{ $request->user->getFullName() }}
                                            </a>
                                        </p>
                                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ __('Request sent: :datetime', ['datetime' => Carbon\Carbon::parse($request->created_at)->translatedFormat('D, d M Y H:i')]) }}</p>
                                    </div>
                                </div>
                                <div class="hidden sm:flex sm:flex-col sm:items-end">
                                    <div class="inline-flex rounded-md shadow-sm" role="group">
                                        <button wire:click="accept({{ $request->user }})" type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                            {{ __('Accept') }}
                                        </button>
                                        <button wire:click="decline({{ $request->user }})" type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                            {{ __('Decline') }}
                                        </button>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
