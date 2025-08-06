<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->orderBy('created_at', 'desc')->get();
        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => ['required', 'string', 'max:1000'],
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'text' => $request->text,
        ]);

        return redirect()->back()->with('success', 'Post created successfully!');
    }
}
