<?php

namespace App\Http\Controllers;

use App\Models\MasterClass;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function confirm(int $id): View|RedirectResponse
    {
        $masterClass = MasterClass::with(['category', 'leader', 'registrations'])
            ->find($id);

        if (! $masterClass) {
            return redirect()
                ->route('home')
                ->with('errorMessage', 'Мастер-класс не найден.');
        }

        $user = auth()->user();

        if (! $user->isVisitor()) {
            return redirect()
                ->route('home')
                ->with('errorMessage', 'Запись доступна только посетителю.');
        }

        $alreadyRegistered = Registration::where('master_class_id', $masterClass->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyRegistered) {
            return redirect()
                ->route('category.show', $masterClass->category_id)
                ->with('errorMessage', 'Вы уже записаны на этот мастер-класс.');
        }

        if (! $masterClass->hasFreePlaces()) {
            return redirect()
                ->route('category.show', $masterClass->category_id)
                ->with('errorMessage', 'Свободных мест больше нет.');
        }

        return view('booking-confirm', [
            'user' => $user,
            'masterClass' => $masterClass,
        ]);
    }

    public function store(int $id): RedirectResponse
    {
        $masterClass = MasterClass::with('registrations')->find($id);

        if (! $masterClass) {
            return redirect()
                ->route('home')
                ->with('errorMessage', 'Мастер-класс не найден.');
        }

        $user = auth()->user();

        if (! $user->isVisitor()) {
            return redirect()
                ->route('home')
                ->with('errorMessage', 'Запись доступна только посетителю.');
        }

        $alreadyRegistered = Registration::where('master_class_id', $masterClass->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyRegistered) {
            return redirect()
                ->route('category.show', $masterClass->category_id)
                ->with('errorMessage', 'Вы уже записаны на этот мастер-класс.');
        }

        if (! $masterClass->hasFreePlaces()) {
            return redirect()
                ->route('category.show', $masterClass->category_id)
                ->with('errorMessage', 'Свободных мест больше нет.');
        }

        Registration::create([
            'master_class_id' => $masterClass->id,
            'user_id' => $user->id,
        ]);

        return redirect()
            ->route('category.show', $masterClass->category_id)
            ->with('successMessage', 'Вы успешно записались на мастер-класс.');
    }

    public function cancel(int $id): RedirectResponse
    {
        $masterClass = MasterClass::find($id);

        if (! $masterClass) {
            return redirect()
                ->route('home')
                ->with('errorMessage', 'Мастер-класс не найден.');
        }

        return redirect()
            ->route('category.show', $masterClass->category_id)
            ->with('errorMessage', 'Запись была отменена.');
    }
}
