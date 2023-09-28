<div>
    <div class="flex">
        @foreach($likeable->likes as $like)
            @if($loop->iteration <= 5)
                <a href="{{ route('athlete.profile', $like->user->id) }}" wire:model="likeable">
                    @if($like->user->getPhoto())
                        <img class="h-5 w-5 mt-2 -pl-4 rounded-full" src="{{ $like->user->getPhoto() }}" alt="{{ $like->user->getFullName() }}" data-tooltip-target="tooltip-athlete-{{ $like->user->id }}">
                    @else
                        <div class="inline-flex items-center justify-center w-5 h-5 mt-2 -pl-4 z-auto overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600" data-tooltip-target="tooltip-athlete-{{ $like->user->id }}">
                            <span class="font-bold text-xs text-gray-600 dark:text-gray-300">
                                {{ $like->user->getInitials() }}
                            </span>
                        </div>
                    @endif
                    <div id="tooltip-athlete-{{ $like->user->id }}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        {{ $like->user->getFullName() }}
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </a>
            @endif
        @endforeach
        @can('like', $model)
            @if($model->getUser()->id !== auth()->id())
                <button wire:click="like({{ $model->id }})" class="inline-flex items-center pl-2 py-2 text-sm font-medium text-gray-500 bg-transparent hover:text-orange-500 dark:text-orange-500">
                    <svg class="h-5 w-5 mr-1" x-description="solid/thumb-up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22" fill="currentColor" aria-hidden="true"><path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path></svg>
                    {{ count($likeable->likes) }}
                </button>
            @endif
        @endcan
        @can('unlike', $model)
            <button wire:click="unlike({{ $model->id }})" class="inline-flex items-center pl-2 py-2 text-sm font-medium text-orange-500 bg-transparent dark:text-orange-500">
                <svg class="h-5 w-5 mr-1" x-description="solid/thumb-up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22" fill="currentColor" aria-hidden="true"><path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path></svg>
                {{ count($likeable->likes) }}
            </button>
        @endcan
    </div>
</div>
