<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function profile()
    {
        $posts = Post::with('user')->get()->map(function ($post) {
            return [
                'id' => $post->id,
                'name' => $post->user->name,
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
