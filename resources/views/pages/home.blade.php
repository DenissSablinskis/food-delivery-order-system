@extends('layouts.app')

@section('main-class', 'home-main')

@section('content')
    <div class="home-content">
        <section class="home-hero" aria-labelledby="home-title">
            <img class="home-hero-image" src="{{ asset('images/home-hero.png') }}" alt="" aria-hidden="true">
            <div class="home-hero-content">
                <p class="home-hero-eyebrow">
                    <span class="bi bi-basket2-fill" aria-hidden="true"></span>
                    {{ __('messages.homeEyebrow') }}
                </p>
                <h1 id="home-title">SIA “GAISMA”</h1>
                <p class="home-hero-slogan">{{ __('messages.homeSlogan') }}</p>
            </div>
        </section>

        <section class="home-about" aria-labelledby="home-about-title">
            <h2 id="home-about-title">{{ __('messages.heading') }}</h2>
            <p data-translate="text">{{ __('messages.text') }}</p>
        </section>
    </div>
@endsection