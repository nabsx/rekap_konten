<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Get dynamic validation rules based on platform
     */
    private function getValidationRules(Request $request)
    {
        $rules = [
            'platform_id' => 'required|exists:platforms,id',
            'posted_at'   => 'required|date',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'url'         => 'nullable|url|max:500',
            'content_type' => 'nullable|in:reels,post',
            'followers'   => 'nullable|integer|min:0',
            'viewers'     => 'nullable|integer|min:0',
            'subscribers' => 'nullable|integer|min:0',
        ];

        // Get platform info
        $platform = Platform::find($request->platform_id);
        if (!$platform) {
            return $rules;
        }

        $platformSlug = $platform->slug;

        // Instagram: content_type is required
        if ($platformSlug === 'instagram') {
            $rules['content_type'] = 'required|in:reels,post';
        }

        return $rules;
    }

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
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki izin untuk membuat postingan.');
        }

        $platforms = Platform::all();
        return view('posts.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki izin untuk membuat postingan.');
        }

        $validated = $request->validate($this->getValidationRules($request));

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
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah postingan.');
        }

        $platforms = Platform::all();
        return view('posts.edit', compact('post', 'platforms'));
    }

    public function update(Request $request, Post $post)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah postingan.');
        }

        $validated = $request->validate($this->getValidationRules($request));

        $post->update($validated);

        return redirect()->route('posts.index')
            ->with('success', 'Postingan berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus postingan.');
        }

        $post->delete();
        return redirect()->route('posts.index')
            ->with('success', 'Postingan berhasil dihapus.');
    }
}
