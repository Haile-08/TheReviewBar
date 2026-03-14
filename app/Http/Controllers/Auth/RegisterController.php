<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        dd($request);
    }
}
