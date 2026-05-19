@extends('layouts.app')

@section('title', 'Добавить мастер-класс')

@section('content')
<div class="row row--nogutter top-line">
    <div class="line"></div>
</div>

<div class="main">
    <div class="row">
        <div class="row--small">
            <form action="{{ route('master-class.store') }}" method="POST">
                @csrf

                <h2>Форма добавления мастер-класса</h2>

                <div class="form-group">
                    <label>Вид творчества</label>
                    <select name="category_id">
                        <option value="">Выберите вид творчества</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Название мастер-класса</label>
                    <input type="text" name="title" value="{{ old('title') }}">
                    @error('title')
                        <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Описание мастер-класса</label>
                    <textarea name="description">{{ old('description') }}</textarea>
                    @error('description')
                        <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Дата</label>
                    <input type="date" id="class_date" name="class_date" value="{{ old('class_date') }}" min="{{ date('Y-m-d') }}">
                    @error('class_date')
                        <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Время</label>
                    <select
                        name="time_slot"
                        id="time_slot"
                        data-busy-slots-url="{{ route('master-class.busy-slots') }}"
                        data-old-time-slot="{{ old('time_slot') }}"
                    >
                        <option value="">Выберите время</option>

                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot }}" @selected(old('time_slot') == $slot)>
                                @if($slot === '09:00')
                                    09:00 - 11:00
                                @elseif($slot === '11:00')
                                    11:00 - 13:00
                                @elseif($slot === '13:00')
                                    13:00 - 15:00
                                @elseif($slot === '15:00')
                                    15:00 - 17:00
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div id="time_slot_hint" style="margin-top:8px; color:#777;"></div>
                    @error('time_slot')
                        <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Количество человек в группе</label>
                    <input type="number" name="capacity" min="1" value="{{ old('capacity') }}">
                    @error('capacity')
                        <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Стоимость мастер-класса</label>
                    <input type="number" name="price" min="0" step="0.01" value="{{ old('price') }}">
                    @error('price')
                        <div style="color:red;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button class="btn" type="submit">Отправить</button>
                    <a href="{{ route('cabinet') }}" class="btn">Назад</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('class_date');
    const timeSelect = document.getElementById('time_slot');
    const hint = document.getElementById('time_slot_hint');

    if (!dateInput || !timeSelect) {
        return;
    }

    const allOptions = Array.from(timeSelect.options).map(option => ({
        value: option.value,
        text: option.text,
        selected: option.selected,
    }));

    function renderOptions(busySlots) {
        const selectedValue = timeSelect.value || timeSelect.dataset.oldTimeSlot || '';

        timeSelect.innerHTML = '';

        allOptions.forEach(optionData => {
            if (optionData.value && busySlots.includes(optionData.value)) {
                return;
            }

            const option = new Option(optionData.text, optionData.value);

            if (optionData.value === selectedValue) {
                option.selected = true;
            }

            timeSelect.add(option);
        });

        const availableSlotsCount = timeSelect.options.length - 1;
        hint.textContent = availableSlotsCount === 0
            ? 'На выбранную дату все временные слоты уже заняты.'
            : '';
    }

    function loadBusySlots() {
        if (!dateInput.value) {
            renderOptions([]);
            return;
        }

        const url = new URL(timeSelect.dataset.busySlotsUrl, window.location.origin);
        url.searchParams.set('date', dateInput.value);

        fetch(url)
            .then(response => response.json())
            .then(busySlots => renderOptions(busySlots))
            .catch(() => renderOptions([]));
    }

    dateInput.addEventListener('change', loadBusySlots);
    loadBusySlots();
});
</script>

@endsection