<main class="container mx-auto px-0 py-12 max-w-screen-lg">
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
            <p class="mb-6">
                <a href="https://doogal.co.uk/SegmentExplorer" target="_blank">Скачать CSV с сегментами</a>
            </p>
            <form wire:submit.prevent="upload">
                <input type="file" wire:model="stravaCSV">
                @error('stravaCSV') <span class="error">{{ $message }}</span> @enderror
            </form>
            <button wire:click="upload" type="button" class="mt-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Обработать сегменты
            </button>
        </div>
        <div class="w-full">
            <button wire:click="processStrava" type="button" class="mt-6 text-white bg-orange-500 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-orange-500 focus:outline-none">
                Добавить обработку сегментов без информации
            </button>
        </div>
    </div>
</main>
