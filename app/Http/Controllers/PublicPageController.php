<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function home(): View
    {
        $doctors = Doctor::where('is_active', true)->orderBy('name')->take(3)->get();

        return view('public.home', compact('doctors'));
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function services(): View
    {
        return view('public.services');
    }

    public function doctors(Request $request): View
    {
        $query = Doctor::where('is_active', true);

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $doctors = $query->orderBy('name')->paginate(9)->withQueryString();

        return view('public.doctors', compact('doctors', 'search'));
    }

    public function contact(): View
    {
        return view('public.contact');
    }
}
