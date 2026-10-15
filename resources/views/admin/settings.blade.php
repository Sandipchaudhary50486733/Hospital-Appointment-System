@extends('layouts.admin')

@section('title', 'Account Settings — Meridian Health')

@section('content')
<main class="max-w-xl mx-auto px-8 py-10">

    @if (session('status'))
        <div class="banner">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="banner error">@foreach ($errors->all() as $error){{ $error }}<br>@endforeach</div>
    @endif

    <h1 class="text-2xl font-extrabold mb-1">Account Settings</h1>
    <p class="text-sm text-slate-500 mb-8">Update your name, email, or password.</p>

    <div class="card">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="field-label">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="field-input">
            </div>

            <div>
                <label class="field-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="field-input">
            </div>

            <div class="border-t border-slate-100 pt-4 mt-2">
                <p class="text-xs font-semibold text-slate-500 mb-3">Leave blank to keep your current password</p>
                <div class="mb-3">
                    <label class="field-label">New password</label>
                    <input type="password" name="password" class="field-input" autocomplete="new-password">
                </div>
                <div>
                    <label class="field-label">Confirm new password</label>
                    <input type="password" name="password_confirmation" class="field-input" autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-3">Save changes</button>
        </form>
    </div>
</main>
@endsection
