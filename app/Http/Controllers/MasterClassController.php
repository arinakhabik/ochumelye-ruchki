<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MasterClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterClassController extends Controller
{
    private array $timeSlots = ['09:00', '11:00', '13:00', '15:00'];

    public function create(): View
    {
        $categories = Category::orderBy('title')->get();

        return view('master-class-create', [
            'categories' => $categories,
            'timeSlots' => $this->timeSlots,
        ]);
    }

    public function busySlots(Request $request): JsonResponse
    {
        $date = $request->query('date');

        if (! $date) {
            return response()->json([]);
        }

        $busySlots = MasterClass::whereDate('class_date', $date)
            ->pluck('time_slot')
            ->map(fn ($slot) => substr($slot, 0, 5))
            ->unique()
            ->values();

        return response()->json($busySlots);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'class_date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot' => ['required', 'in:09:00,11:00,13:00,15:00'],
            'capacity' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
        ], [
            'category_id.required' => 'Выберите вид творчества.',
            'category_id.exists' => 'Выбранный вид творчества не найден.',

            'title.required' => 'Введите название мастер-класса.',
            'title.max' => 'Название не должно превышать 255 символов.',

            'description.required' => 'Введите описание мастер-класса.',

            'class_date.required' => 'Выберите дату.',
            'class_date.date' => 'Введите корректную дату.',
            'class_date.after_or_equal' => 'Нельзя выбрать прошедшую дату.',

            'time_slot.required' => 'Выберите время.',
            'time_slot.in' => 'Выберите время из доступных слотов.',

            'capacity.required' => 'Укажите количество человек.',
            'capacity.integer' => 'Количество человек должно быть числом.',
            'capacity.min' => 'Количество человек должно быть не меньше 1.',

            'price.required' => 'Укажите стоимость.',
            'price.numeric' => 'Стоимость должна быть числом.',
            'price.min' => 'Стоимость не может быть отрицательной.',
        ]);

        $isBusy = MasterClass::whereDate('class_date', $validated['class_date'])
            ->where('time_slot', $validated['time_slot'])
            ->exists();

        if ($isBusy) {
            return back()
                ->withErrors([
                    'time_slot' => 'Это время уже занято другим мастер-классом. Выберите другой слот.',
                ])
                ->withInput();
        }

        MasterClass::create([
            'category_id' => $validated['category_id'],
            'leader_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'class_date' => $validated['class_date'],
            'time_slot' => $validated['time_slot'],
            'capacity' => $validated['capacity'],
            'price' => $validated['price'],
        ]);

        return redirect()
            ->route('cabinet')
            ->with('successMessage', 'Мастер-класс успешно добавлен.');
    }

    public function edit(int $id): View|RedirectResponse
    {
        $masterClass = MasterClass::where('leader_id', auth()->id())->find($id);

        if (! $masterClass) {
            return redirect()
                ->route('cabinet')
                ->with('errorMessage', 'Мастер-класс не найден.');
        }

        return view('master-class-edit', [
            'masterClass' => $masterClass,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $masterClass = MasterClass::where('leader_id', auth()->id())->find($id);

        if (! $masterClass) {
            return redirect()
                ->route('cabinet')
                ->with('errorMessage', 'Мастер-класс не найден.');
        }

        $validated = $request->validate([
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
        ], [
            'description.required' => 'Введите описание мастер-класса.',
            'price.required' => 'Укажите стоимость.',
            'price.numeric' => 'Стоимость должна быть числом.',
            'price.min' => 'Стоимость не может быть отрицательной.',
        ]);

        $masterClass->update([
            'description' => $validated['description'],
            'price' => $validated['price'],
        ]);

        return redirect()
            ->route('master-class.show', $masterClass->id)
            ->with('successMessage', 'Мастер-класс успешно обновлён.');
    }
}
