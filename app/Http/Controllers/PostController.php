<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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

        return Inertia::render('Dashboard/Dashboard', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }

    public function search(Request $request) {
        $query = $request->query('query');

        if (!$query) return response()->json(['results' => []]);

        $response = Http::withToken(config('services.tmdb.token'))
            ->get("https://api.themoviedb.org/3/search/movie", [
                'query' => $query,
                'include_adult' => false,
                'language' => 'en-US',
            ]);

        return $response->json();
    }
}
