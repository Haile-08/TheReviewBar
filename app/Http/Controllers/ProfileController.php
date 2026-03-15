<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        $posts = $user->post()
            ->with('user')
            ->latest()
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'name' => $post->user->name, // Now 'user' is loaded and available
                    'description' => $post->description,
                    'poster' => $post->poster,
                    'movie' => $post->movie,
                    'created_at_human' => $post->created_at->diffForHumans(),
                ];
            });

        return Inertia::render('Profile/Profile', [
            'posts' => $posts,
        ]);
    }
}
