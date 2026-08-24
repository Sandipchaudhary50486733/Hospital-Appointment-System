<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:150'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // No persistence layer in this trimmed build — wire this up to a
        // Mailable (Mail::to('care@meridianhealth.test')->send(...)) or a
        // contact_messages table if you need to keep a record of inquiries.

        return back()->with('status', 'Thanks for reaching out — our team will get back to you within one business day.');
    }
}
