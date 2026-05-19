<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MasterClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeaderCabinetController extends Controller
{
    public function index(): View
    {
        $leader = auth()->user();

        $categories = Category::orderBy('title')->get();

        $masterClasses = MasterClass::with(['category', 'registrations.user'])
            ->where('leader_id', $leader->id)
            ->orderBy('class_date')
            ->orderBy('time_slot')
            ->get();

        return view('cabinet', [
            'leader' => $leader,
            'categories' => $categories,
            'masterClasses' => $masterClasses,
        ]);
    }

    public function show(int $id): View|RedirectResponse
    {
        $leader = auth()->user();

        $categories = Category::orderBy('title')->get();

        $masterClass = MasterClass::with(['category', 'leader', 'registrations.user'])
            ->where('leader_id', $leader->id)
            ->find($id);

        if (! $masterClass) {
            return redirect()
                ->route('cabinet')
                ->with('errorMessage', 'Мастер-класс не найден.');
        }

        return view('master-class-show', [
            'leader' => $leader,
            'categories' => $categories,
            'masterClass' => $masterClass,
        ]);
    }
}
