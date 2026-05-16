@extends('layouts.app')

@section('title', 'View User - CE&P Internal Audit System')
@section('header', 'User Details')
@section('subheader', 'Detailed information about the user.')

@section('content')
<div class="bg-white rounded-3xl premium-shadow border border-slate-100 p-8">
    <div class="flex items-center gap-6 border-b border-slate-100 pb-6 mb-6">
        <img class="h-20 w-20 rounded-2xl object-cover ring-4 ring-indigo-50 shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366F1&color=fff&size=128" alt="" />
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800">{{ $user->name }}</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">{{ $user->email }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">User Code</h3>
            <p class="text-base font-semibold text-slate-800">{{ $user->user_code ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Phone Number</h3>
            <p class="text-base font-semibold text-slate-800">{{ $user->phone_number ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Gender</h3>
            <p class="text-base font-semibold text-slate-800">{{ $user->gender ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Role</h3>
            <div>
                @if($user->role === 'admin')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider bg-purple-50 text-purple-600 border border-purple-200">
                        Administrator
                    </span>
                @elseif($user->role === 'super_user')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider bg-sky-50 text-sky-600 border border-sky-200">
                        Super User
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                        Normal User
                    </span>
                @endif
            </div>
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Department</h3>
            <p class="text-base font-semibold text-slate-800">{{ $user->department->name ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Status</h3>
            <div>
                @if($user->status === 'active')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-rose-50 text-rose-600 border border-rose-200">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Inactive
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="pt-8 border-t border-slate-100 mt-8 flex items-center justify-start gap-4">
        <a href="{{ route('users.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">Back to List</a>
        <a href="{{ route('users.edit', $user->id) }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-colors shadow-sm">Edit User</a>
    </div>
</div>
@endsection
