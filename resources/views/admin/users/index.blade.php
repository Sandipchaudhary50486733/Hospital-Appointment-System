@extends('layouts.admin')

@section('title', 'Users — Meridian Health')

@section('content')
<main class="max-w-5xl mx-auto px-8 py-10">

    <h1 class="text-2xl font-extrabold mb-1">Users</h1>
    <p class="text-sm text-slate-500 mb-6">Patients and doctors registered on the platform.</p>

    <div class="flex gap-2 mb-6">
        <a href="{{ route('admin.users.index', ['role' => 'patient']) }}" class="text-xs font-bold rounded-full px-4 py-2 {{ $role === 'patient' ? 'bg-brand-500 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Patients</a>
        <a href="{{ route('admin.users.index', ['role' => 'doctor']) }}" class="text-xs font-bold rounded-full px-4 py-2 {{ $role === 'doctor' ? 'bg-brand-500 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Doctors</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wide text-slate-500 border-b border-slate-100">
                    <th class="py-2.5 pr-3">Name</th><th class="py-2.5 pr-3">Email</th><th class="py-2.5 pr-3">Phone</th><th class="py-2.5">Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="py-3 pr-3 font-semibold">{{ $user->name }}</td>
                        <td class="py-3 pr-3">{{ $user->email }}</td>
                        <td class="py-3 pr-3">{{ $user->phone ?? '—' }}</td>
                        <td class="py-3 text-xs">{{ $user->created_at->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-sm text-slate-500 py-8">No {{ $role }}s yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</main>
@endsection
