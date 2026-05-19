<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'ОчУмелые ручки')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body class="@yield('body_class')">

    <div class="header">
        <div style="display:flex; align-items:center; justify-content:space-between; width:100%; padding:15px 35px; box-sizing:border-box; background:#fff;">
            <div style="width:220px;">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Логотип" style="width:190px;">
                </a>
            </div>

            <div style="flex:1; text-align:center; color:#00044c; font-size:28px; font-weight:700; line-height:1.15;">
                Клуб любителей творчества<br>
                «Очумелые ручки»
            </div>

            <div style="width:420px; display:flex; align-items:center; justify-content:flex-end; gap:14px; color:#00044c; font-size:16px; font-weight:700; white-space:nowrap;">
                <a href="{{ route('home') }}" style="color:#00044c; text-decoration:none;">Главная</a>

                @auth
                    <span style="color:#00044c; white-space:nowrap;">
                        {{ auth()->user()->name }}
                    </span>

                    @if(auth()->user()->isLeader())
                        <a href="{{ route('cabinet') }}" style="color:#00044c; text-decoration:none;">Кабинет</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" style="display:inline; margin:0;">
                        @csrf
                        <button type="submit" style="background:none; border:none; padding:0; color:#00044c; font-size:16px; font-weight:700; cursor:pointer;">
                            Выход
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" style="color:#00044c; text-decoration:none;">Вход</a>
                    <span>/</span>
                    <a href="{{ route('register') }}" style="color:#00044c; text-decoration:none;">Регистрация</a>
                @endauth
            </div>
        </div>
    </div>

    @if(session('successMessage'))
        <div class="row" style="padding:15px; color:green; font-weight:bold;">
            {{ session('successMessage') }}
        </div>
    @endif

    @if(session('errorMessage'))
        <div class="row" style="padding:15px; color:red; font-weight:bold;">
            {{ session('errorMessage') }}
        </div>
    @endif

    @yield('content')

    <div class="row row--nogutter">
        <div class="line"></div>
    </div>

    <div class="footer">
        <div class="row">
            <div class="row--small grid between">
                <div class="address">Наш адрес: ВДНХ, 120в</div>
                <div class="tel">Тел: 89123456765</div>
                <div class="copy">(с) Copyright, 2017</div>
            </div>
        </div>
    </div>

</body>
</html>