<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter') === 'unread' ? 'unread' : 'all';

        $messages = ContactMessage::with('user')
            ->when($filter === 'unread', fn ($q) => $q->whereNull('read_at'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $unreadCount = ContactMessage::whereNull('read_at')->count();

        return view('admin.messages.index', compact('messages', 'filter', 'unreadCount'));
    }

    public function show(ContactMessage $message): View
    {
        // Opening a message marks it as read.
        if (! $message->isRead()) {
            $message->update(['read_at' => now()]);
        }

        $message->load('user');

        return view('admin.messages.show', compact('message'));
    }

    /** Toggle read / unread. */
    public function update(ContactMessage $message): RedirectResponse
    {
        $message->update(['read_at' => $message->isRead() ? null : now()]);

        return back()->with('status', $message->isRead() ? 'Marked as read.' : 'Marked as unread.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()
            ->route('admin.messages.index')
            ->with('status', 'Message deleted.');
    }
}
