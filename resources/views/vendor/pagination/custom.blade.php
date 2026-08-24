@if ($paginator->hasPages())
<nav class="flex items-center gap-1.5 flex-wrap">
    @if ($paginator->onFirstPage())
        <span class="px-3 py-1.5 rounded-lg text-slate-300 border border-slate-200 text-sm">&laquo;</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-slate-700 border border-slate-200 text-sm hover:bg-slate-50">&laquo;</a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-2.5 py-1.5 text-slate-400 text-sm">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3 py-1.5 rounded-lg bg-brand-500 text-white text-sm font-bold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-3 py-1.5 rounded-lg text-slate-700 border border-slate-200 text-sm hover:bg-slate-50">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-slate-700 border border-slate-200 text-sm hover:bg-slate-50">&raquo;</a>
    @else
        <span class="px-3 py-1.5 rounded-lg text-slate-300 border border-slate-200 text-sm">&raquo;</span>
    @endif
</nav>
@endif
