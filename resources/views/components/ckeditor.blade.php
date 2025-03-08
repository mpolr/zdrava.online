@props([
    'route' => Route::currentRouteName(),
    'attr' => '',
    'model' => $model,
])

@section('js')
    @vite('resources/css/ckeditor.css')
    @vite('resources/js/ckeditor.js')
@endsection

<div wire:ignore>
    <div id="component-data" data-route="{{ $route }}" data-attr="{{ $attr }}"></div>
    <div class="py-6">
        <div id="editor" class="h-[400px]">{!! $model !!}</div>
    </div>
</div>
