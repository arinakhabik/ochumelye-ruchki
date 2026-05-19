<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MasterClass;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('title')->get();

        $userRegistrations = collect();

        if (auth()->check() && auth()->user()->isVisitor()) {
            $userRegistrations = auth()->user()
                ->registrations()
                ->with(['masterClass.category', 'masterClass.leader'])
                ->latest()
                ->get();
        }

        return view('home', [
            'categories' => $categories,
            'userRegistrations' => $userRegistrations,
        ]);
    }

    public function category(int $id): View
    {
        $categories = Category::orderBy('title')->get();

        $category = Category::with(['masterClasses.leader', 'masterClasses.registrations'])
            ->findOrFail($id);

        $masterClasses = MasterClass::with(['leader', 'registrations'])
            ->where('category_id', $category->id)
            ->orderBy('class_date')
            ->orderBy('time_slot')
            ->get();

        return view('category', [
            'categories' => $categories,
            'category' => $category,
            'masterClasses' => $masterClasses,
        ]);
    }
}
