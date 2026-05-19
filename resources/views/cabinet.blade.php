@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
<div class="main">
    <div class="row" style="background: url('{{ asset('img/bg.jpg') }}') center/cover no-repeat; min-height: 470px; padding: 55px 0 70px;">
        <div class="row--small grid between" style="align-items: flex-start; gap: 45px;">

            <div style="background: #fff; width: 650px; min-height: 330px; padding: 35px 45px; box-shadow: 0 5px 20px rgba(0,0,0,.15);">
                
                <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 35px;">
                    <img
                        src="{{ asset('img/' . ($leader->photo ?? 'driver1.png')) }}"
                        alt="{{ $leader->name }}"
                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; flex-shrink: 0;"
                    >

                    <div>
                        <h2 style="margin: 0 0 18px; color: #00044c; font-size: 28px; line-height: 1.2;">
                            {{ $leader->name }}
                        </h2>

                        <a href="{{ route('master-class.create') }}"
                           style="display:inline-block; padding: 12px 28px; border: 3px solid #214a78; color:#214a78; text-decoration:none; font-weight:bold;">
                            Добавить мастер-класс
                        </a>
                    </div>
                </div>

                <h2 style="color:#214a78; margin: 0 0 25px; font-size: 30px;">
                    Мои мастер-классы
                </h2>

                @if($masterClasses->isEmpty())
                    <p>У вас пока нет мастер-классов.</p>
                @else
                    @foreach($masterClasses as $masterClass)
                        <div style="display:grid; grid-template-columns: 110px 1fr; gap:20px; padding:20px 0; border-top:1px solid #d7d7d7;">
                            <div style="font-weight:bold; color:#214a78;">
                                {{ $masterClass->class_date->format('d.m.Y') }}<br>
                                {{ $masterClass->time_slot }}
                            </div>

                            <div>
                                <b>{{ $masterClass->title }}</b><br>
                                Вид творчества: {{ $masterClass->category->title }}<br>
                                Стоимость: {{ $masterClass->price }} руб.<br>
                                Свободных мест: {{ $masterClass->freePlaces() }}<br>

                                <a href="{{ route('master-class.show', $masterClass->id) }}">Подробнее</a><br>
                                <a href="{{ route('master-class.edit', $masterClass->id) }}">Редактировать</a>

                                @if($masterClass->registrations->isNotEmpty())
                                    <p><b>Участники:</b></p>
                                    @foreach($masterClass->registrations as $index => $registration)
                                        <p>
                                            {{ $index + 1 }}. {{ $registration->user->name }}<br>
                                            email: {{ $registration->user->email }}<br>
                                            tel: {{ $registration->user->phone }}
                                        </p>
                                    @endforeach
                                @else
                                    <p>Участников пока нет.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <ul class="menu" style="margin-top:0;">
                @foreach($categories as $category)
                    <li>
                        <a href="{{ route('category.show', $category->id) }}">
                            {{ $category->title }}
                        </a>
                    </li>
                @endforeach
            </ul>

        </div>
    </div>
</div>
@endsection