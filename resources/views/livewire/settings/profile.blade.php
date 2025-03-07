@section('title', __('My profile') . ' | Zdrava')
<div class="container mx-auto px-0 py-12">
    <div class="flex sm:flex-row md:flex-row flex-col gap-4">
        <div>
            @include('components.settings-menu')
        </div>
        <div class="w-full">
            <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black dark:text-gray-100">
                {{ __('My profile') }}
            </h2>
            <hr class="h-px mt-4 mb-8 bg-gray-200 border-0 dark:bg-gray-700">
            @error('profile') @livewire('toast.errors') @enderror
            @if (session()->get('success'))
                @livewire('toast.success')
            @endif
            <span class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Photo') }}</span>
            <div class="flex items-center justify-center w-full" id="drop-zone">
                <label for="photo" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        @if (auth()->user()->getPhoto())
                            <img src="{{ auth()->user()->getPhoto() }}"
                                 alt="{{ auth()->user()->getFullName() }}"
                                 loading="lazy" id="userPhoto"
                                 class="w-32 h-32 mb-3 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" />
                        @else
                            <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        @endif
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">{{ __('Click to select file') }}</span> {{ __('or drag and drop') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG {{ __('or') }} GIF ({{ __('max.') }} 512x512px)</p>
                    </div>
                    <input wire:model="photo" wire:change.debounce="save(true)" id="photo" name="photo" type="file" class="hidden" accept="image/*" />
                </label>
            </div>
            <div class="grid pt-8 gap-6 sm:grid-cols-2">
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('First name') }}</label>
                    <input wire:model="user.first_name" type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" autocomplete="off" placeholder="" required>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Last name') }}</label>
                    <input wire:model="user.last_name" type="text" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" autocomplete="off" placeholder="" required>
                </div>
                <div>
                    <label for="nickname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Nickname') }}</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">@</span>
                        <input wire:model="user.nickname" type="text" id="nickname" class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                    </div>
                </div>
                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" value="{{ auth()->user()->email }}" id="phone" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" autocomplete="off" disabled readonly required>
                </div>
                <p class="pt-4">
                    <button wire:click="save(false)" type="button" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-full">{{ __('Save') }}</button>
                </p>
            </div>
        </div>
    </div>
    <script nonce="{{ csp_nonce() }}">
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.querySelector('#drop-zone input');

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();

            const file = e.dataTransfer.files[0];
            if (file) {
                fileInput.files = e.dataTransfer.files;
                //const event = new Event('change', { bubbles: true });
                //fileInput.dispatchEvent(event);
            }
        });

        Livewire.on('fileUploaded', (url) => {
            const image = document.querySelector('#drop-zone img');
            if (image) {
                image.src = url;
            }
        });
    </script>
</div>
