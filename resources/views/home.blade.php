@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="main">
        <div class="row">
            <div class="hover"></div>
            <div class="title">Очумелые ручки</div>

            <div class="row--small grid between">
                <div class="content">
                    <h2>О компании</h2>
                    <p>
                        Клуб любителей творчества «Очумелые ручки» проводит мастер-классы по различным видам творчества.
                        У нас можно выбрать интересующее направление, записаться на мастер-класс и принять участие в занятиях с опытными ведущими.
                    </p>

                    @auth
                        @if(auth()->user()->isVisitor())
                            <h2 style="margin-top: 30px;">Мои записи</h2>

                            @if($userRegistrations->isEmpty())
                                <p>Вы пока не записаны ни на один мастер-класс.</p>
                            @else
                                @foreach($userRegistrations as $registration)
                                    <div style="margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                                        <strong>{{ $registration->masterClass->title }}</strong><br>
                                        Вид творчества: {{ $registration->masterClass->category->title }}<br>
                                        Ведущий: {{ $registration->masterClass->leader->name }}<br>
                                        Дата: {{ $registration->masterClass->class_date->format('d.m.Y') }}<br>
                                        Время: {{ $registration->masterClass->time_slot }}
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    @endauth
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