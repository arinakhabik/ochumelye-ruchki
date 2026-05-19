@extends('layouts.app')

@section('title', 'Редактировать мастер-класс')

@section('content')
<div class="row row--nogutter top-line">
    <div class="line"></div>
</div>

<div class="main">
    <div class="row">
        <div class="row--small">
            <form action="{{ route('master-class.update', $masterClass->id) }}" method="POST">
                @csrf

                <h2>Редактирование мастер-класса</h2>

                <p><b>Название:</b> {{ $masterClass->title }}</p>
                <p><b>Дата:</b> {{ $masterClass->class_date->format('d.m.Y') }}</p>
                <p><b>Время:</b> {{ $masterClass->time_slot }}</p>

                <div class="form-group">
                    <label>Описание мастер-класса</label>
                    <textarea name="description">{{ old('description', $masterClass->description) }}</textarea>
                    @error('description') <div style="color:red;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Стоимость мастер-класса</label>
                    <input type="number" name="price" min="0" step="0.01" value="{{ old('price', $masterClass->price) }}">
                    @error('price') <div style="color:red;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <button class="btn" type="submit">Сохранить</button>
                    <a href="{{ route('master-class.show', $masterClass->id) }}" class="btn">Назад</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection