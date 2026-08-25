<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Article::with('category')
            ->where('user_id', Auth::id())
            ->where('is_active', 1);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        $articles = $query->latest()->paginate(10);

        return view('articles.index', compact('articles', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        Article::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id' => Auth::id(),
            'is_active' => 1,
        ]);

        return redirect()->route('articles.index');
    }

    public function edit(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $article->update($validated);

        return redirect()->route('articles.index');
    }

    public function destroy(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403);
        }

        $article->delete();

        return redirect()->route('articles.index');
    }
        public function test_owner_can_delete_their_own_article(): void
    {
        $owner = User::factory()->create();
        $category = Category::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($owner)->delete(route('articles.destroy', $article));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    // 👇 YENİ METODU BURAYA EKLE
    public function test_user_can_create_article(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('articles.store'), [
            'title' => 'Yeni Makale',
            'content' => 'İçerik',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('articles.index'));
        $this->assertDatabaseHas('articles', [
            'title' => 'Yeni Makale',
            'user_id' => $user->id,
        ]);
    }

} // 👈 class'ı kapatan son parantez, bundan sonra hiçbir şey olmamalı
