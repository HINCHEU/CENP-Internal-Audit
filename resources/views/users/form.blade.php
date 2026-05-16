@extends('layouts.app')

@section('title', 'User Form - CE&P Internal Audit System')
@section('header', 'User Details')
@section('subheader', 'Create or edit user profiles and roles.')

@section('content')
<div class="max-w-4xl bg-white rounded-3xl premium-shadow border border-slate-100 p-8 sm:p-12">
    <form action="{{ $user->exists ? route('users.update', $user->id) : route('users.store') }}" method="POST" class="space-y-10">
        @csrf
        @if($user->exists)
            @method('PUT')
        @endif
        
        <!-- Personal Information -->
        <div>
            <div class="flex items-center gap-4 border-b border-slate-100 pb-6 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100 shrink-0">
                    <i class="ph ph-identification-card text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Personal Information</h3>
                    <p class="text-sm font-medium text-slate-500 mt-1">Basic details for the user profile.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Full Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="e.g. HIN CHEU" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                    @error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">User Code</label>
                    <input type="text" name="user_code" value="{{ old('user_code', $user->user_code) }}" placeholder="e.g. P3881" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                    @error('user_code')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="it02@cenp.com" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                    @error('email')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="+855 123456789" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                    @error('phone_number')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Gender</label>
                    <select name="gender" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender', $user->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $user->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $user->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Account Settings -->
        <div>
            <div class="flex items-center gap-4 border-b border-slate-100 pb-6 mb-8 mt-10">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                    <i class="ph ph-shield-check text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Account Settings</h3>
                    <p class="text-sm font-medium text-slate-500 mt-1">Configure role, access, and security.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Role <span class="text-rose-500">*</span></label>
                    <select name="role" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                        <option value="normal_user" {{ old('role', $user->role) === 'normal_user' ? 'selected' : '' }}>Normal User (Auditor)</option>
                        <option value="super_user" {{ old('role', $user->role) === 'super_user' ? 'selected' : '' }}>Super User</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Department</label>
                    <select name="department_id" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                        <option value="">Select a department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full px-5 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-700">
                    @error('password')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-100 flex items-center justify-end gap-4 mt-10">
            <a href="{{ route('users.index') }}" class="px-6 py-3.5 text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-800 rounded-xl font-bold transition-all">Cancel</a>
            <button type="submit" class="px-6 py-3.5 text-white bg-gradient-primary hover:opacity-90 rounded-xl font-bold transition-all shadow-lg shadow-indigo-500/30 flex items-center gap-2 premium-hover">
                Save User
            </button>
        </div>
    </form>
</div>
@endsection
