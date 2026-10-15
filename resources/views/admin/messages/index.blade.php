@extends('layouts.admin')

@section('title', 'Messages — Meridian Health')

@section('content')
<main class="max-w-5xl mx-auto px-8 py-10">

    <h1 class="text-2xl font-extrabold mb-1">Messages</h1>
    <p class="text-sm text-slate-500 mb-6">Messages sent from the Contact Us form by patients, doctors and visitors.</p>

    <div class="flex gap-2 mb-6">
        <a href="{{ route('admin.messages.index') }}"
           class="text-xs font-bold rounded-full px-4 py-2 {{ $filter === 'all' ? 'bg-brand-500 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">All</a>
        <a href="{{ route('admin.messages.index', ['filter' => 'unread']) }}"
           class="text-xs font-bold rounded-full px-4 py-2 {{ $filter === 'unread' ? 'bg-brand-500 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">
            Unread @if ($unreadCount > 0)({{ $unreadCount }})@endif
        </a>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-slate-500 border-b border-slate-100">
                    <th class="py-2.5 pr-3">From</th>
                    <th class="py-2.5 pr-3">Subject</th>
                    <th class="py-2.5 pr-3">Sent as</th>
                    <th class="py-2.5 pr-3">Received</th>
                    <th class="py-2.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($messages as $message)
                    <tr class="border-b border-slate-50 last:border-0 {{ $message->isRead() ? '' : 'bg-brand-50/50' }}">
                        <td class="py-3 pr-3">
                            <a href="{{ route('admin.messages.show', $message) }}" class="{{ $message->isRead() ? 'font-semibold' : 'font-extrabold' }} hover:text-brand-600">
                                @unless ($message->isRead())
                                    <span class="inline-block w-2 h-2 rounded-full bg-brand-500 mr-1.5"></span>
                                @endunless
                                {{ $message->name }}
                            </a>
                            <div class="text-xs text-slate-500">{{ $message->email }}</div>
                        </td>
                        <td class="py-3 pr-3">
                            <span class="{{ $message->isRead() ? '' : 'font-bold' }}">{{ $message->subject ?: '(no subject)' }}</span>
                            <div class="text-xs text-slate-400">{{ \Illuminate\Support\Str::limit($message->message, 60) }}</div>
                        </td>
                        <td class="py-3 pr-3 text-xs">{{ $message->senderType() }}</td>
                        <td class="py-3 pr-3 text-xs whitespace-nowrap">{{ $message->created_at->format('M j, Y g:i A') }}</td>
                        <td class="py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.messages.show', $message) }}" class="btn-ghost text-xs">Open</a>
                            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" class="inline"
                                  data-confirm="Delete this message?" data-confirm-button="Yes, delete it">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-outline text-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-sm text-slate-500 py-8">
                        {{ $filter === 'unread' ? 'No unread messages.' : 'No messages yet.' }}
                    </td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $messages->links() }}</div>
    </div>
</main>
@endsection
