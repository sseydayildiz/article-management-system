<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <header class="bg-white border-b border-slate-200 py-4">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 flex items-center justify-between">
            <a href="{{ route('front.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-indigo-600 transition">
                ← Tüm Yazılara Dön
            </a>
        </div>
    </header>

    <article class="max-w-4xl mx-auto px-4 sm:px-6 py-10">

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Makale Başlık & Bilgiler -->
        <div class="space-y-3 border-b border-slate-200 pb-6 mb-8">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span class="font-bold text-indigo-600">{{ $article->category->name ?? 'Genel' }}</span>
                <span>•</span>
                <span>{{ $article->created_at->format('d.m.Y') }}</span>
            </div>

            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ $article->title }}
            </h1>

            <p class="text-xs text-slate-500">Yazar: <span class="font-semibold text-slate-700">{{ $article->user->name ?? 'Yazar' }}</span></p>
        </div>

        <!-- Makale İçeriği -->
        <div class="prose prose-slate max-w-none text-slate-700 text-base leading-relaxed space-y-4 mb-12">
            {!! nl2br(e($article->content)) !!}
        </div>

        <!-- Yorumlar Bölümü -->
        <section class="mt-12 pt-6 border-t border-slate-200 space-y-8">
            <h3 class="text-xl font-bold text-slate-900">
                Yorumlar ({{ $comments->count() }})
            </h3>

            <div class="space-y-4">
                @forelse($comments as $comment)
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-sm text-slate-800">{{ $comment->name }}</span>
                            <span class="text-[11px] text-slate-400">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $comment->comment }}</p>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic">Henüz yorum yapılmamış.</p>
                @endforelse
            </div>

            <!-- Yorum Formu -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                <h4 class="text-sm font-bold text-slate-900 mb-4">Yorum Yap</h4>
                <form action="{{ route('front.comment.store', $article->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Adınız</label>
                        <input type="text" name="name" required class="w-full rounded-lg border-slate-200 text-xs p-2.5 border focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Adınız">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Yorumunuz</label>
                        <textarea name="comment" rows="3" required class="w-full rounded-lg border-slate-200 text-xs p-2.5 border focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Yorumunuzu yazın..."></textarea>
                    </div>

                    <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition">
                        Yorumu Gönder
                    </button>
                    <p class="text-[11px] text-slate-400 mt-1">Yorumunuz yazar onayından sonra yayınlanacaktır.</p>
                </form>
            </div>
        </section>

    </article>

</body>
</html>