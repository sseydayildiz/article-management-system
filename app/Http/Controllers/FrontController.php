<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * Anasayfa: Son Yazılar / En Çok Okunanlar
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $sort = $request->query('sort', 'latest');

        $query = Article::with(['category', 'user']);

        // Kategori filtresi
        if ($request->filled('category')) {
            $query->where('category_id', $request->query('category'));
        }

        // Sıralama (Son Yazılar veya En Çok Okunanlar)
        if ($sort === 'popular') {
            $query->orderBy('reads', 'desc');
        } else {
            $query->latest();
        }

        $articles = $query->paginate(6);

        return view('front.index', compact('articles', 'categories', 'sort'));
    }

    /**
     * Makale Detay: Okunma sayısını (+1) artırır.
     */
    public function show(string $id)
    {
        $article = Article::with(['category', 'user'])->findOrFail($id);

        // Her görüntülemede okunma sayısını artır
        $article->increment('reads');

        $comments = Comment::where('article_id', $article->id)
            ->where('is_approved', 1)
            ->latest()
            ->get();

        return view('front.show', compact('article', 'comments'));
    }

    /**
     * Yorum Gönderme
     */
    public function storeComment(Request $request, string $articleId)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'comment' => 'required|string|max:1000',
        ]);

        $article = Article::findOrFail($articleId);

        Comment::create([
            'article_id'  => $article->id,
            'name'        => $request->name,
            'comment'     => $request->comment,
            'is_approved' => 0,
            'is_active'   => 1,
        ]);

        return back()->with('success', 'Yorumunuz başarıyla iletildi. Yazar onayından sonra yayınlanacaktır.');
    }
}