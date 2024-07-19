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
            <a href="https://play.google.com/apps/test/online.zdrava/7" target="_blank">
                <img src="https://d3nn82uaxijpm6.cloudfront.net/assets/i18n/ru-RU/marketing/btn-google-play-491d5ac8feeeb218e88f5f6175fe4308a616b133d7a07c03fbf4736e82e9bc3d.svg" alt="Zdrava on Google play">
            </a>
        </p>
        <br>
        <br>
        <div class="content-center">
            <img src="https://zdrava.online/zdrava_01.jpg" alt="Zdrava" width="320">
        </div>
    </div>
@endsection
