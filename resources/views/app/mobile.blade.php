@extends('layouts.site')
@section('title', __('Zdrava android app') . ' | Zdrava')
@section('content')
    <div class="container my-12 px-6 mx-auto">
        <h1 class="text-4xl md:text-5xl xl:text-6xl font-bold tracking-tight mb-12">
            {{ __('GPS app for runners and cyclists Zdrava') }}
        </h1>
        <p>{{ __('With Zdrava, you can track your runs and rides via GPS, complete fun challenges, share training photos, and make new friends.') }}</p>
        <br>
        <br>
        <p class="content-center">
            <a href="https://www.rustore.ru/catalog/app/online.zdrava" target="_blank">
                <img src="https://zdrava.online/rustore-logo-monochrome-dark.svg" width="188" height="63" alt="Скачать Здрава из RuStore">
            </a>
        </p>
    </div>
@endsection
