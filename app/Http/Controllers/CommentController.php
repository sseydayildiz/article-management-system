<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Yazarın makalelerine gelen yorumları listeler.
     */
    public function index()
    {
        $comments = Comment::whereHas('article', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->with('article')
        ->latest()
        ->paginate(15);

        return view('comments.index', compact('comments'));
    }

    /**
     * Yorumu onaylar (is_approved = 1).
     */
    public function approve(string $id)
    {
        $comment = Comment::whereHas('article', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $comment->update(['is_approved' => 1]);

        log_user_action('comment_approved', [
            'comment_id' => $comment->id,
            'article_id' => $comment->article_id,
        ]);

        return redirect()->route('comments.index')->with('success', 'Yorum başarıyla onaylandı.');
    }

    /**
     * Yorumu pasife çeker (is_active = 0).
     */
    public function destroy(string $id)
    {
        $comment = Comment::whereHas('article', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $comment->update(['is_active' => 0]);

        log_user_action('comment_deleted', [
            'comment_id' => $comment->id,
            'article_id' => $comment->article_id,
        ]);

        return redirect()->route('comments.index')->with('success', 'Yorum silindi.');
    }
}
