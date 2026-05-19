@extends('layouts.app')

@section('title', 'Мастер-класс')
@section('body_class', 'dp')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title"></div>

        <div class="row--small grid between">
            <div class="content driver-page">
                <div class="driver-page-photo">
                    <img src="{{ asset('img/' . ($leader->photo ?? 'driver-page.png')) }}">
                </div>

                <div class="driver-page-name">{{ $leader->name }}</div>

                <div class="driver-page-text">
                    <div class="driver-page-my">{{ $masterClass->title }}</div>

                    <p><b>Вид творчества:</b> {{ $masterClass->category->title }}</p>
                    <p><b>Описание:</b> {{ $masterClass->description }}</p>
                    <p><b>Дата:</b> {{ $masterClass->class_date->format('d.m.Y') }}</p>
                    <p><b>Время:</b> {{ $masterClass->time_slot }}</p>
                    <p><b>Стоимость:</b> {{ $masterClass->price }} руб.</p>
                    <p><b>Количество мест:</b> {{ $masterClass->capacity }}</p>
                    <p><b>Свободных мест:</b> {{ $masterClass->freePlaces() }}</p>

                    <h2 style="margin-top: 25px;">Участники</h2>

                    @if($masterClass->registrations->isNotEmpty())
                        <table class="driver-page-table">
                            <tbody>
                            @foreach($masterClass->registrations as $index => $registration)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <b>{{ $registration->user->name }}</b><br>
                                        email: {{ $registration->user->email }}<br>
                                        tel: {{ $registration->user->phone }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>На этот мастер-класс пока никто не записался.</p>
                    @endif
                </div>

                <div class="driver-page-btn-wrapper">
                    <a href="{{ route('master-class.edit', $masterClass->id) }}" class="driver-page-btn btn">
                        Редактировать
                    </a>
                    <a href="{{ route('cabinet') }}" class="driver-page-btn btn">
                        Назад в кабинет
                    </a>
                </div>
            </div>

            <ul class="menu">
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