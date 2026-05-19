@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="row row--nogutter top-line">
    <div class="line"></div>
</div>

<div class="main">
    <div class="row">
        <div class="row--small">
            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <h2>Форма регистрации</h2>

                <div class="form-group">
                    <label>ФИО</label>
                    <input type="text" name="name" value="{{ old('name') }}">
                    @error('name') <div style="color:red;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}">
                    @error('email') <div style="color:red;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password">
                    @error('password') <div style="color:red;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Номер телефона</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}">
                    @error('phone') <div style="color:red;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <button class="btn" type="submit">Отправить</button>
                </div>

                <div class="form-group">
                    <a href="{{ route('login') }}">Уже есть аккаунт? Войти</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection