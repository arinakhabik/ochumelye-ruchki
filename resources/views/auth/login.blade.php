@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <div class="main">
        <div class="row">
            <div class="row--small">
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <h2>Форма входа</h2>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div style="color:red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Пароль</label>
                        <input type="password" name="password">
                        @error('password')
                            <div style="color:red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button class="btn" type="submit">Войти</button>
                    </div>

                    <div class="form-group">
                        <a href="{{ route('register') }}">Нет аккаунта? Зарегистрироваться</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection