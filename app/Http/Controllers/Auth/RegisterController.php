<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('profile_picture')) {
            Storage::disk('private')->put('profile_picture', $request->profile_picture);
        }

        
    }
}
