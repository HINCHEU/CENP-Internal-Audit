@extends('layouts.app')

@section('title', 'Users - CE&P Internal Audit System')
@section('header', 'User Management')
@section('subheader', 'Manage system users and their roles.')

@section('content')
<div id="module-app" class="bg-white rounded-3xl premium-shadow border border-slate-100 overflow-hidden flex flex-col">
    <!-- Toolbar -->
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white">
        <div class="relative w-full sm:w-[320px]">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
            <input v-model="search" type="text" placeholder="Search users..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all focus:bg-white">
        </div>
        <a href="{{ route('users.create') }}" class="w-full sm:w-auto bg-gradient-primary hover:opacity-90 text-white px-6 py-2.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/30 premium-hover">
            <i class="ph ph-user-plus text-xl"></i> Add User
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold border-b border-slate-100">
                    <th class="px-8 py-5">User</th>
                    <th class="px-6 py-5">Role</th>
                    <th class="px-6 py-5">Department</th>
                    <th class="px-6 py-5">Status</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $u)
                <tr v-show="!search || '{{ strtolower(addslashes($u->name . ' ' . $u->email . ' ' . ($u->department->name ?? '') . ' ' . $u->role)) }}'.includes(search.toLowerCase())" class="hover:bg-slate-50 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <img class="h-10 w-10 rounded-full object-cover ring-2 ring-indigo-50" src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=6366F1&color=fff" alt="" />
                            <div>
                                <p class="text-slate-800 font-bold text-sm">{{ $u->name }}</p>
                                <p class="text-[11px] font-semibold text-slate-500 mt-0.5">{{ $u->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        @if($u->role === 'admin')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-purple-600 border border-purple-200">
                                Administrator
                            </span>
                        @elseif($u->role === 'super_user')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-sky-50 text-sky-600 border border-sky-200">
                                Super User
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                Normal User
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-slate-600 font-medium text-sm">{{ $u->department->name ?? '-' }}</td>
                    <td class="px-6 py-5">
                        @if($u->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('users.show', $u->id) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="View">
                                <i class="ph ph-eye text-lg"></i>
                            </a>
                            <a href="{{ route('users.edit', $u->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </a>
                            <form id="delete-user-{{ $u->id }}" action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-user-{{ $u->id }}', 'this user')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-8 text-center text-slate-500 font-medium">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const { createApp, ref } = Vue;
        createApp({
            setup() {
                const search = ref('');
                return { search };
            }
        }).mount('#module-app');
    });
</script>
@endsection
