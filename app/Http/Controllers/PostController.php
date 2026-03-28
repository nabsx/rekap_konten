<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('platform', 'user')->orderByDesc('posted_at');

        // Filter by platform
        if ($request->filled('platform_id')) {
            $query->where('platform_id', $request->platform_id);
        }

        // Filter by month/year
        if ($request->filled('month') && $request->filled('year')) {
            $query->byMonth($request->year, $request->month);
        } elseif ($request->filled('year')) {
            $query->byYear($request->year);
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts     = $query->paginate(15)->withQueryString();
        $platforms = Platform::all();

        return view('posts.index', compact('posts', 'platforms'));
    }

    public function create()
    {
        $platforms = Platform::all();
        return view('posts.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'posted_at'   => 'required|date',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'url'         => 'nullable|url|max:500',
        ]);

        $validated['user_id'] = Auth::id();

        Post::create($validated);

        return redirect()->route('posts.index')
            ->with('success', 'Postingan berhasil ditambahkan.');
    }

    public function show(Post $post)
    {
        $post->load('platform', 'user');
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $platforms = Platform::all();
        return view('posts.edit', compact('post', 'platforms'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'posted_at'   => 'required|date',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'url'         => 'nullable|url|max:500',
        ]);

        $post->update($validated);

        return redirect()->route('posts.index')
            ->with('success', 'Postingan berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')
            ->with('success', 'Postingan berhasil dihapus.');
    }
}
