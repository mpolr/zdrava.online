@props([
	'shape' => 'circle',
	'class' => 'w-12 h-12',
    'image' => null,
    'name' => null,
])
@php
    $avatarShapeClass = [
        'rounded' => 'rounded-md',
        'square' => 'rounded-lg',
        'circle' => 'rounded-full',
    ][$shape];
@endphp
<div class="inline-flex flex-shrink-0 overflow-hidden bg-gray-100 {{ $avatarShapeClass }} {{ $class }}">
    @if ($image)
        <img
             src="{{ $image }}"
             alt="{{ $name }}"
             class="object-fit"
             loading="lazy"
        />
    @else
        <a href="{{ route('settings.profile') }}">
            <div class="relative inline-flex items-center justify-center {{ $class }} mb-3 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                <span class="font-bold text-3xl text-gray-600 dark:text-gray-300">
                    {!! \App\Models\User::getUserInitials($name) !!}
                </span>
            </div>
        </a>
    @endif
</div>
