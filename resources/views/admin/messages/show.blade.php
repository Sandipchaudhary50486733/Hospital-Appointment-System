@extends('layouts.admin')

@section('title', 'Message — Meridian Health')

@section('content')
<main class="max-w-3xl mx-auto px-8 py-10">

    <a href="{{ route('admin.messages.index') }}" class="text-sm text-slate-500 hover:text-brand-600">
        <i class="fa-solid fa-arrow-left mr-1"></i> Back to messages
    </a>

    <h1 class="text-2xl font-extrabold mt-3 mb-1">{{ $message->subject ?: '(no subject)' }}</h1>
    <p class="text-sm text-slate-500 mb-6">Received {{ $message->created_at->format('M j, Y \a\t g:i A') }}</p>

    <div class="card">
        <dl class="grid grid-cols-[110px_1fr] gap-y-2 text-sm mb-5">
            <dt class="font-bold text-slate-500">From</dt>
            <dd>{{ $message->name }}</dd>

            <dt class="font-bold text-slate-500">Email</dt>
            <dd><a href="mailto:{{ $message->email }}" class="text-brand-600">{{ $message->email }}</a></dd>

            <dt class="font-bold text-slate-500">Sent as</dt>
            <dd>
                {{ $message->senderType() }}
                @if ($message->user && $message->user->email !== $message->email)
                    <span class="text-xs text-slate-400">(account: {{ $message->user->email }})</span>
                @endif
            </dd>
        </dl>

        <div class="rounded-xl bg-slate-50 border border-slate-100 p-4 text-sm leading-relaxed">{!! nl2br(e($message->message)) !!}</div>

        <div class="flex flex-wrap gap-2 mt-6">
            <a class="btn-primary text-sm px-4 py-2"
               href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . ($message->subject ?: 'Your message to Meridian Health')) }}">
                <i class="fa-solid fa-reply mr-1"></i> Reply by email
            </a>

            <form method="POST" action="{{ route('admin.messages.update', $message) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-ghost text-sm">Mark as {{ $message->isRead() ? 'unread' : 'read' }}</button>
            </form>

            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}"
                  data-confirm="Delete this message?" data-confirm-button="Yes, delete it">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-outline text-sm">Delete</button>
            </form>
        </div>
    </div>
</main>
@endsection
