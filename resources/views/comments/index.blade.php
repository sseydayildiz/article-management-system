<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gelen Yorumlar') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase">
                            <th class="py-3 px-4">Makale</th>
                            <th class="py-3 px-4">Yorum Yapan</th>
                            <th class="py-3 px-4">Yorum</th>
                            <th class="py-3 px-4">Durum</th>
                            <th class="py-3 px-4">Tarih</th>
                            <th class="py-3 px-4 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($comments as $comment)
                            <tr>
                                <td class="py-3 px-4 font-medium text-slate-900">
                                    {{ $comment->article->title ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-slate-700">
                                    {{ $comment->name }}
                                </td>
                                <td class="py-3 px-4 text-slate-600 text-xs max-w-xs truncate">
                                    {{ $comment->comment }}
                                </td>
                                <td class="py-3 px-4 text-xs">
                                    @if($comment->is_approved)
                                        <span class="px-2 py-0.5 rounded bg-green-100 text-green-700 font-semibold">Onaylı</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-700 font-semibold">Onay Bekliyor</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-500 text-xs">
                                    {{ $comment->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="py-3 px-4 text-right space-x-2">
                                    @if(!$comment->is_approved)
                                        <form action="{{ route('comments.approve', $comment->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-indigo-600 hover:underline text-xs font-semibold">Onayla</button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline" onsubmit="return confirm('Bu yorumu silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs font-semibold">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 px-4 text-center text-slate-400 text-xs">
                                    Henüz gelen yorum bulunmuyor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($comments->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $comments->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>