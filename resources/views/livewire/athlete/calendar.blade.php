@php use Carbon\Carbon; @endphp
<div class="container mx-auto px-0 py-12">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold mb-2">Календарь тренировок</h1>
        <div class="flex items-center space-x-2">
            @if ($year > 1990)
                <a href="{{ route('athlete.calendar', $year - 1) }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 rounded text-gray-600 p-2 hover:bg-gray-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                </a>
            @endif
            <div class="text-lg font-semibold">{{ $year }}</div>
            @if ($year < Carbon::now()->year)
                <a href="{{ route('athlete.calendar', $year + 1) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 rounded text-gray-600 p-2 hover:bg-gray-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            @endif
        </div>
    </div>

    <!-- Wrapper for weekly overview and stats -->
    <div class="flex items-start mb-6">
        <!-- Weekly overview -->
        <div class="flex-1 h-20 bg-gray-100 mr-4">
            <div class="flex h-full">
                @foreach (range(1, 52) as $week)
                    <div class="flex-1 border-r border-gray-300"></div>
                @endforeach
            </div>
        </div>

        <!-- Stats -->
        <div class="flex space-x-8 min-w-max">
            <div class="text-center">
                <span class="text-2xl font-semibold text-blue-600">{{ $stats['duration'] }}</span>
                <p class="text-gray-600">Часов</p>
            </div>
            <div class="text-center">
                <span class="text-2xl font-semibold">{{ $stats['distance'] }}</span>
                <p class="text-gray-600">Км</p>
            </div>
            <div class="text-center">
                <span class="text-2xl font-semibold">{{ $stats['activities'] }}</span>
                <p class="text-gray-600">Тренировки</p>
            </div>
        </div>
    </div>

    <!-- Monthly grid -->
    <div class="grid grid-cols-4 gap-4 w-full">
        @foreach (range(1, 12) as $monthNumber)
            @php
                $monthName = Carbon::parse("$year-$monthNumber")->isoFormat('MMMM');
                $monthlyActivityCount = $monthlyStats[$monthNumber]['count'] ?? 0; // Количество тренировок
                $monthlyDuration = $monthlyStats[$monthNumber]['duration'] ?? 0; // Суммарная продолжительность тренировок
                $monthlyDurationInHours = number_format($monthlyDuration / 3600, 1, '.', ''); // Продолжительность в часах
            @endphp
            <div class="border border-gray-300 p-4">
                <h2 class="text-lg font-bold mb-2">{{ mb_strtoupper(__($monthName)) }}</h2>
                <div class="text-xl font-semibold">{{ $monthlyActivityCount }} тренировок</div>
                <div class="mt-2 text-sm text-gray-600">~{{ trans_choice('plurals.hour', ceil($monthlyDurationInHours)) }}</div>
                <div class="mt-2 flex space-x-1">
                    @for ($i = 0; $i < $monthlyActivityCount; $i++)
                        <div class="h-6 w-1 bg-black"></div>
                    @endfor
                </div>
            </div>
        @endforeach
    </div>
</div>
