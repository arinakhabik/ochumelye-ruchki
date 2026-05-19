@extends('layouts.app')

@section('title', $category->title)

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">{{ $category->title }}</div>

        <div class="row--small grid between" style="align-items: flex-start;">

            <div class="content" style="max-width: 650px; padding: 30px; background: #fff;">
                @if($category->image)
                    <img src="{{ asset('img/' . $category->image) }}" style="
                        width: 220px;
                        height: auto;
                        float: left;
                        margin: 0 25px 15px 0;
                        border-radius: 10px;
                    ">
                @endif

                <p style="margin: 0; line-height: 1.6; text-align: left;">
                    {{ $category->description }}
                </p>

                <div style="clear: both;"></div>
            </div>

            <ul class="menu">
                @foreach($categories as $item)
                    <li>
                        <a href="{{ route('category.show', $item->id) }}">
                            {{ $item->title }}
                        </a>
                    </li>
                @endforeach
            </ul>

        </div>

        <div class="row shedule">
            <div class="row--small">
                <h2>Расписание</h2>

                <div class="drivers">
                    @forelse($masterClasses as $masterClass)
                        <div class="driver" style="
                            display: grid;
                            grid-template-columns: 1fr 190px;
                            gap: 30px;
                            align-items: center;
                            margin-bottom: 35px;
                        ">
                            <div style="display: flex; gap: 25px; align-items: flex-start;">
                                <div class="driver-photo" style="flex-shrink: 0;">
                                    <img src="{{ asset('img/' . ($masterClass->leader->photo ?? 'driver1.png')) }}">
                                </div>

                                <div class="driver-text">
                                    <div class="driver-name">
                                        {{ $masterClass->leader->name }}
                                    </div>

                                    <div class="driver-desc">
                                        <b>{{ $masterClass->title }}</b><br>
                                        {{ $masterClass->description }}<br><br>
                                        Стоимость: {{ $masterClass->price }} руб.<br>
                                        Свободных мест: {{ $masterClass->freePlaces() }}

                                        @if(!$masterClass->hasFreePlaces())
                                            <br>
                                            <span style="color:#ffdddd; font-weight:bold;">
                                                Мест больше нет
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div style="
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                                gap: 12px;
                            ">
                                @auth
                                    @if(auth()->user()->isVisitor())
                                        @if($masterClass->hasFreePlaces())
                                            <a href="{{ route('booking.confirm', $masterClass->id) }}" class="driver-btn" style="
                                                display:inline-block;
                                                text-align:center;
                                                min-width:150px;
                                            ">
                                                записаться
                                            </a>
                                        @else
                                            <div style="
                                                border: 2px solid #fff;
                                                padding: 10px 18px;
                                                min-width: 150px;
                                                text-align: center;
                                                color: #fff;
                                                opacity: .7;
                                            ">
                                                нет мест
                                            </div>
                                        @endif
                                    @endif
                                @endauth

                                <div class="driver-time" style="text-align:center;">
                                    {{ $masterClass->class_date->format('d.m.Y') }}<br>
                                    {{ $masterClass->time_slot }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="color: white;">Пока мастер-классов нет.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection