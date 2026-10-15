@extends('layouts.app')

@section('main-class', 'profile-main')

@section('content')
    <section class="profile-card profile-edit-card">
        <div class="profile-card-heading">
            <span class="profile-avatar" aria-hidden="true">
                <i class="bi bi-person-fill"></i>
            </span>
            <div>
                <p class="profile-eyebrow">{{ __('messages.profileSubheader') }}</p>
                <h1>{{ __('messages.profileUpdate') }}</h1>
            </div>
        </div>

        <!-- Lietotāja datu rediģešanas forma. Ja validācija nav izieta zem formas tiek paradīts kļūdas paziņojums -->

        <form method="post" action="{{ route('profile.update') }}" class="profile-edit-form">
            @csrf
            @method('PUT')

            <div class="profile-form-field">
                <label for="email">{{ __('messages.profileEmail') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="profile-form-field">
                <label for="name">{{ __('messages.profileName') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="profile-form-field">
                <label for="surname">{{ __('messages.profileSurname') }}</label>
                <input type="text" id="surname" name="surname" value="{{ old('surname', auth()->user()->surname) }}" required>
                @error('surname')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="profile-form-field">
                <label for="username">{{ __('messages.profileUsername') }}</label>
                <input type="text" id="username" name="username" value="{{ old('username', auth()->user()->username) }}" required>
                @error('username')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="profile-form-field">
                <label for="address">{{ __('messages.profileAddress') }}</label>
                <input type="text" id="address" name="address" value="{{ old('address', auth()->user()->address) }}" required>
                @error('address')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="profile-form-actions">
                <button class="profile-edit-link" type="submit">{{ __('messages.profileSave') }}</button>
            </div>
        </form>

        <!-- Forma paroles rediģēšanai -->

        <div class="profile-card-heading">
            <span class="profile-avatar" aria-hidden="true">
                <i class="bi bi-key-fill"></i>
            </span>
            <div>
                <h1>{{ __('messages.profileUpdatePassword') }}</h1>
            </div>
        </div>

        <form method="post" action="{{ route('profile.update-password') }}" class="profile-edit-form">
            @csrf
            @method('PUT')
            
            <div class="profile-form-field">
                <label for="current_password">{{ __('messages.profileCurrentPassword') }}</label>
                <input type="password" id="current_password" name="current_password" required>
                @error('current_password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="profile-form-field">
                <label for="password">{{ __('messages.profilePassword') }}</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="profile-form-field">
                <label for="password_confirmation">{{ __('messages.profilePasswordConfirmation') }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                @error('password_confirmation')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="profile-form-actions">
                <a href="{{ route('profile.show') }}" class="profile-cancel-link">{{ __('messages.profileBack') }}</a>
                <button class="profile-edit-link" type="submit">{{ __('messages.profilePasswordSave') }}</button>
            </div>

        </form>

    </section>
@endsection
