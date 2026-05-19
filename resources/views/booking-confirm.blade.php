@extends('layouts.app')

@section('title', 'Подтверждение записи')

@section('content')
<div class="row row--nogutter top-line">
    <div class="line"></div>
</div>

<div class="main">
    <div class="row">
        <div class="row--small">
            <div style="width:50%; margin:0 auto; padding-top:30px; padding-bottom:10px;">
                <h2>Подтверждение записи</h2>

                <p><b>ФИО пользователя:</b> {{ $user->name }}</p>
                <p><b>Вид творчества:</b> {{ $masterClass->category->title }}</p>
                <p><b>Мастер-класс:</b> {{ $masterClass->title }}</p>
                <p><b>ФИО мастера:</b> {{ $masterClass->leader->name }}</p>
                <p><b>Дата:</b> {{ $masterClass->class_date->format('d.m.Y') }}</p>
                <p><b>Время:</b> {{ $masterClass->time_slot }}</p>
                <p><b>Стоимость:</b> {{ $masterClass->price }} руб.</p>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <form action="{{ route('booking.store', $masterClass->id) }}" method="POST" style="width:auto;margin:0;padding:0;">
                        @csrf
                        <button class="btn" type="submit">Подтвердить запись</button>
                    </form>

                    <form action="{{ route('booking.cancel', $masterClass->id) }}" method="POST" style="width:auto;margin:0;padding:0;">
                        @csrf
                        <button class="btn" type="submit">Отменить</button>
                    </form>
                </div>

                <p style="margin-top:20px;">
                    <a href="{{ route('category.show', $masterClass->category_id) }}">Вернуться к расписанию</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection