<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        if ($search) {
            $articles = Article::where('title', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->latest()
                ->get();
        } else {
            $articles = Article::latest()->get();
        }
    
        return view('clubinfo', compact('articles'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $article = new Article();
        $article->title = $validatedData['title'];
        $article->description = $validatedData['description'];
        $article->user_id = auth()->id();
    
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $article->image = $path;
        }
    
        $article->save();
    
        return redirect()->route('clubinfo')->with('success', 'Article created successfully.');
    }


    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
{
    $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $data = $request->all();

    if ($request->hasFile('image')) {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        $data['image'] = $request->file('image')->store('articles', 'public');
    }

    $article->update($data);

    return redirect()->route('articles.index')->with('success', 'Article updated successfully.');
}

public function show(Article $article)
{
    return view('articles.show', compact('article'));
}

    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Article deleted successfully.');
    }
}
