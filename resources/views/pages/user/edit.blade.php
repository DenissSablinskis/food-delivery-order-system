@extends('layouts.app')

@section('content')

    <h1 class="form-title">Rediģēt profilu</h1>

    <form method='post' action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')
    
        <label for="email">{{ __('messages.registerEmail') }}</label>
        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
        @error('email')
            <div class="field-error">{{ $message }}</div>
        @enderror
       
        <label for="username">{{ __('messages.registerUsername') }}</label>
        <input type="text" id="username" name="username" value="{{ old('username', auth()->user()->username) }}" required>
        @error('username')
            <div class="field-error">{{ $message }}</div>
        @enderror

        <label for="address">Adrese</label>
        <input type="text" id="address" name="address" value="{{ old('address', auth()->user()->address) }}" required>
        @error('address')
            <div class="field-error">{{ $message }}</div>
        @enderror
    
        <button class="submit-btn" type="submit">Saglabāt</button>
    </form>

@endsection