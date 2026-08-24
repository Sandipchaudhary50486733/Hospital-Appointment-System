{{--
    Simple read-only star display.
    Usage: <x-star-rating :rating="$doctor->average_rating" :count="$doctor->ratings_count" />
--}}
@props(['rating' => null, 'count' => 0])

<div class="flex items-center gap-1">
    @if ($rating)
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= round($rating))
                <i class="fa-solid fa-star text-amber-400 text-sm"></i>
            @else
                <i class="fa-regular fa-star text-slate-300 text-sm"></i>
            @endif
        @endfor
        <span class="text-xs font-semibold text-slate-600 ml-1">{{ $rating }}</span>
        <span class="text-xs text-slate-400">({{ $count }})</span>
    @else
        <span class="text-xs text-slate-400">No ratings yet</span>
    @endif
</div>
