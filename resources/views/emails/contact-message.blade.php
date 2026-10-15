<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.5;">
    <h2 style="margin-bottom: 4px;">New contact message</h2>
    <p style="color: #64748b; margin-top: 0;">Received {{ $contact->created_at->format('M j, Y g:i A') }}</p>

    <table cellpadding="6" style="border-collapse: collapse;">
        <tr><td><strong>From</strong></td><td>{{ $contact->name }} &lt;{{ $contact->email }}&gt;</td></tr>
        <tr><td><strong>Sent as</strong></td><td>{{ $contact->senderType() }}</td></tr>
        <tr><td><strong>Subject</strong></td><td>{{ $contact->subject ?: '—' }}</td></tr>
    </table>

    <div style="margin-top: 16px; padding: 14px; background: #f1f5f9; border-radius: 8px;">
        {!! nl2br(e($contact->message)) !!}
    </div>

    <p style="margin-top: 20px;">
        <a href="{{ route('admin.messages.show', $contact) }}">Open in the admin inbox</a>
        &middot; Reply to this email to answer {{ $contact->name }} directly.
    </p>
</body>
</html>
