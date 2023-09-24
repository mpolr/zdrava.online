<div class="flex">
@foreach($model->likes as $like)
    @if($loop->iteration <= 5)
        <a href="{{ route('athlete.profile', $like->user->id) }}">
            @if($like->user->getPhoto())
                <img class="h-5 w-5 mt-0.5 flex-none rounded-full bg-gray-50" src="{{ $like->user->getPhoto() }}" alt="{{ $like->user->getFullName() }}" data-tooltip-target="tooltip-athlete-{{ $like->user->id }}">
            @else
                <div class="inline-flex items-center justify-center w-5 h-5 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600" data-tooltip-target="tooltip-athlete-{{ $like->user->id }}">
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
</div>

@can('like', $model)
    @if($model->getUser()->id !== auth()->id())
        <form action="{{ route('activities.like') }}" method="POST">
            @csrf
            <input type="hidden" name="likeable_type" value="{{ get_class($model) }}"/>
            <input type="hidden" name="id" value="{{ $model->id }}"/>
            <button class="inline-flex items-center px-1 py-2 text-sm font-medium text-gray-500 bg-transparent hover:text-black focus:text-orange-500 dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" /></svg>
                {{ count($model->likes) }}
            </button>
        </form>
    @endif
@endcan

@can('unlike', $model)
    <form action="{{ route('activities.unlike') }}" method="POST">
        @csrf
        @method('DELETE')
        <input type="hidden" name="likeable_type" value="{{ get_class($model) }}"/>
        <input type="hidden" name="id" value="{{ $model->id }}"/>
        <button class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-transparent hover:text-black focus:text-orange-500 dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" /></svg>
            {{ count($model->likes) }}
        </button>
    </form>
@endcan
