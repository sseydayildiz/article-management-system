<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Makalelerim') }}
            </h2>
            <a href="{{ route('articles.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                + Yeni Makale Ekle
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Kategori Filtresi -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
                <form method="GET" action="{{ route('articles.index') }}" class="flex items-center gap-3">
                    <label for="category_id" class="text-xs font-semibold text-slate-600">Kategoriye Göre Filtrele:</label>
                    <select name="category_id" id="category_id" onchange="this.form.submit()" class="rounded-lg border-slate-200 text-xs border p-2 focus:ring-indigo-500">
                        <option value="">Tüm Kategoriler</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    @if(request('category_id'))
                        <a href="{{ route('articles.index') }}" class="text-xs text-red-600 hover:underline">
                            Filtreyi Temizle
                        </a>
                    @endif
                </form>
            </div>

            <!-- Makaleler Tablosu -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase">
                            <th class="py-3 px-4">Başlık</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Tarih</th>
                            <th class="py-3 px-4 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($articles as $article)
                            <tr>
                                <td class="py-3 px-4 font-medium text-slate-900">
                                    <a href="{{ route('front.show', $article->id) }}" target="_blank" class="text-indigo-600 hover:underline">
                                        {{ $article->title }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-slate-700">
                                    {{ $article->category->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-slate-500 text-xs">
                                    {{ $article->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="py-3 px-4 text-right space-x-2">
                                    <a href="{{ route('articles.edit', $article->id) }}" class="text-blue-600 hover:underline text-xs font-semibold">Düzenle</a>
                                    
                                    <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="inline" onsubmit="return confirm('Bu makaleyi silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs font-semibold">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 px-4 text-center text-slate-400 text-xs">
                                    Henüz makale bulunmuyor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($articles->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $articles->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>