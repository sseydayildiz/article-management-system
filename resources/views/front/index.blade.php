<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen py-8">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 space-y-6">

        <!-- Mockup Üst Butonları -->
        <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <div class="flex items-center gap-2">
                <a href="{{ route('front.index', ['sort' => 'latest']) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ ($sort ?? 'latest') === 'latest' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Son Yazılar
                </a>
                <a href="{{ route('front.index', ['sort' => 'popular']) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ ($sort ?? '') === 'popular' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    En Çok Okunanlar
                </a>
            </div>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-green-600 text-white hover:bg-green-700 transition">
                        Yazar Paneli
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-100 border border-slate-200 transition">
                        Sisteme Giriş
                    </a>
                    <a href="{{ route('register') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        Kayıt Ol
                    </a>
                @endauth
            </div>
        </div>

        <!-- Makale Listesi -->
        <div class="space-y-6">
            @forelse($articles as $article)
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs space-y-3">
                    <h2 class="text-xl font-bold text-slate-900 hover:text-indigo-600 transition">
                        <a href="{{ route('front.show', $article->id) }}">
                            {{ $article->title }}
                        </a>
                    </h2>

                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ Str::limit(strip_tags($article->content), 200) }}
                    </p>

                    <div class="pt-2">
                        <a href="{{ route('front.show', $article->id) }}" class="text-xs font-bold text-indigo-600 hover:underline">
                            Devamını oku >>
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white p-8 rounded-xl border border-slate-200 text-center text-xs text-slate-500">
                    Henüz yayınlanmış bir makale bulunmuyor.
                </div>
            @endforelse
        </div>

        <!-- Sayfalama -->
        <div>
            {{ $articles->appends(request()->query())->links() }}
        </div>

    </div>

</body>
</html>