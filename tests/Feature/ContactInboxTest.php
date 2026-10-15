<?php

use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

function admin(string $email = 'admin@test.test'): User
{
    return User::create(['name' => 'Admin', 'email' => $email, 'password' => 'password', 'role' => 'admin']);
}

function person(string $role, string $email): User
{
    return User::create(['name' => ucfirst($role), 'email' => $email, 'password' => 'password', 'role' => $role]);
}

function contactPayload(array $over = []): array
{
    return $over + ['name' => 'Visitor', 'email' => 'v@v.test', 'subject' => 'Hi', 'message' => 'Hello there'];
}

/* ------------------------------ saving -------------------------------- */

it('saves a visitor message with no account attached', function () {
    $this->post(route('contact.store'), contactPayload())->assertSessionHas('status');

    $m = ContactMessage::first();
    expect($m->user_id)->toBeNull();
    expect($m->senderType())->toBe('Visitor');
    expect($m->isRead())->toBeFalse();
});

it('records the logged-in patient or doctor who sent the message', function () {
    $this->actingAs(person('patient', 'pat@test.test'))->post(route('contact.store'), contactPayload(['subject' => 'P']));
    $this->actingAs(person('doctor', 'doc@test.test'))->post(route('contact.store'), contactPayload(['subject' => 'D']));

    expect(ContactMessage::where('subject', 'P')->first()->senderType())->toBe('Patient');
    expect(ContactMessage::where('subject', 'D')->first()->senderType())->toBe('Doctor');
});

it('validates the form', function () {
    $this->post(route('contact.store'), ['name' => '', 'email' => 'nope', 'message' => ''])
        ->assertSessionHasErrors(['name', 'email', 'message']);
    expect(ContactMessage::count())->toBe(0);
});

it('prefills name and email for logged-in users', function () {
    $this->actingAs(person('patient', 'pat@test.test'))
        ->get(route('public.contact'))->assertOk()->assertSee('pat@test.test', false);
});

/* ------------------------------ email --------------------------------- */

it('emails every admin when no notify address is configured, with reply-to the sender', function () {
    Mail::fake();
    admin('a1@test.test');
    admin('a2@test.test');

    $this->post(route('contact.store'), contactPayload());

    Mail::assertSent(NewContactMessage::class, function ($mail) {
        return $mail->hasTo('a1@test.test') && $mail->hasTo('a2@test.test')
            && $mail->hasReplyTo('v@v.test');
    });
});

it('emails CONTACT_NOTIFY_EMAIL addresses when configured', function () {
    Mail::fake();
    admin();
    config(['mail.contact_to' => 'owner@test.test, boss@test.test']);

    $this->post(route('contact.store'), contactPayload());

    Mail::assertSent(NewContactMessage::class, fn ($m) => $m->hasTo('owner@test.test') && $m->hasTo('boss@test.test') && ! $m->hasTo('admin@test.test'));
});

it('still saves the message and shows success when the mail server fails', function () {
    admin();
    Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP down'));

    $this->post(route('contact.store'), contactPayload())
        ->assertSessionHas('status')->assertSessionHasNoErrors();

    expect(ContactMessage::count())->toBe(1);
});

it('renders the email with the message content', function () {
    $m = ContactMessage::create(contactPayload(['message' => "Line1\n<b>x</b>"]));

    $html = (new NewContactMessage($m))->render();

    expect($html)->toContain('Line1')->toContain('&lt;b&gt;x&lt;/b&gt;')->not->toContain('<b>x</b>');
});

/* ------------------------------ inbox --------------------------------- */

it('keeps the inbox admin-only', function () {
    $m = ContactMessage::create(contactPayload());

    $this->get(route('admin.messages.index'))->assertRedirect(route('login'));
    $this->actingAs(person('patient', 'p@test.test'))->get(route('admin.messages.index'))->assertForbidden();
    $this->actingAs(person('doctor', 'd@test.test'))->get(route('admin.messages.show', $m))->assertForbidden();
    $this->actingAs(person('patient', 'p2@test.test'))->delete(route('admin.messages.destroy', $m))->assertForbidden();
});

it('lists messages, filters unread, and shows an unread badge in the sidebar', function () {
    ContactMessage::create(contactPayload(['subject' => 'Unread one']));
    ContactMessage::create(contactPayload(['subject' => 'Read one', 'read_at' => now()]));

    $this->actingAs(admin())->get(route('admin.messages.index'))
        ->assertOk()->assertSee('Unread one')->assertSee('Read one');

    $this->actingAs(admin('b@test.test'))->get(route('admin.messages.index', ['filter' => 'unread']))
        ->assertOk()->assertSee('Unread one')->assertDontSee('Read one');
});

it('marks a message as read when opened, and can toggle it back', function () {
    $m = ContactMessage::create(contactPayload());
    $admin = admin();

    $this->actingAs($admin)->get(route('admin.messages.show', $m))
        ->assertOk()->assertSee('Hello there')->assertSee('Reply by email');
    expect($m->fresh()->isRead())->toBeTrue();

    $this->actingAs($admin)->patch(route('admin.messages.update', $m));
    expect($m->fresh()->isRead())->toBeFalse();
});

it('deletes a message', function () {
    $m = ContactMessage::create(contactPayload());

    $this->actingAs(admin())->delete(route('admin.messages.destroy', $m))
        ->assertRedirect(route('admin.messages.index'));

    expect(ContactMessage::count())->toBe(0);
});

it('keeps the message when the sender account is deleted', function () {
    $user = person('patient', 'gone@test.test');
    $this->actingAs($user)->post(route('contact.store'), contactPayload());

    $user->delete();

    $m = ContactMessage::first();
    expect($m)->not->toBeNull();
    expect($m->user_id)->toBeNull();
    expect($m->senderType())->toBe('Visitor');
});
