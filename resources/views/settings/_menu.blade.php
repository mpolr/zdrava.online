<div class="w-64 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    <a href="{{ route('settings.profile') }}" aria-current="true"
       @if(Route::currentRouteName() == 'settings.profile')
           class="block w-full px-4 py-2 text-white bg-blue-700 border-b border-gray-200 rounded-t-lg cursor-pointer dark:bg-gray-800 dark:border-gray-600"
       @else
            class="block w-full px-4 py-2 border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white"
       @endif
    >{{ __('My profile') }}</a>

    <a href="{{ route('settings.account') }}" aria-current="true"
       @if(Route::currentRouteName() == 'settings.account')
           class="block w-full px-4 py-2 text-white bg-blue-700 border-b border-gray-200 cursor-pointer dark:bg-gray-800 dark:border-gray-600"
       @else
           class="block w-full px-4 py-2 border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white"
        @endif
    >{{ __('My account') }}</a>

    <a href="{{ route('settings.privacy') }}" aria-current="true"
       @if(Route::currentRouteName() == 'settings.privacy')
           class="block w-full px-4 py-2 text-white bg-blue-700 border-b border-gray-200 rounded-b-lg cursor-pointer dark:bg-gray-800 dark:border-gray-600"
       @else
           class="block w-full px-4 py-2 border-b border-gray-200 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white"
        @endif
    >{{ __('Privacy') }}</a>

</div>
