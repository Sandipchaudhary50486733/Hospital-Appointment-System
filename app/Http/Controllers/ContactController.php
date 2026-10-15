<?php

namespace App\Http\Controllers;

use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:150'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // Saved first, so the message is never lost even if email fails.
        // user_id is null for visitors who are not logged in.
        $contact = ContactMessage::create($validated + [
            'user_id' => $request->user()?->id,
        ]);

        $this->notifyAdmins($contact);

        return back()->with('status', 'Thanks for reaching out — our team will get back to you within one business day.');
    }

    /**
     * Email the message to CONTACT_NOTIFY_EMAIL, or to every admin account.
     * A mail-server problem must never break the form for the visitor.
     */
    private function notifyAdmins(ContactMessage $contact): void
    {
        $configured = collect(explode(',', (string) config('mail.contact_to')))
            ->map(fn ($email) => trim($email))
            ->filter();

        $recipients = $configured->isNotEmpty()
            ? $configured
            : User::where('role', 'admin')->pluck('email');

        if ($recipients->isEmpty()) {
            return;
        }

        try {
            Mail::to($recipients->unique()->values()->all())->send(new NewContactMessage($contact));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
