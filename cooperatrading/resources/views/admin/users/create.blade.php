@extends('layouts.admin')

@section('title', 'New Admin User')

@section('header', 'Create Admin User')
@section('subheader', 'Add a new administrative team member and assign their access permissions')

@section('actions')
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
        &larr; Back to Admin Users
    </a>
@endsection

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf

        <!-- Account Credentials -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm space-y-4">
            <h2 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Account Credentials</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-300 @enderror"
                        placeholder="e.g. John Doe">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-300 @enderror"
                        placeholder="e.g. name@cooperatrading.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required minlength="8"
                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-300 @enderror"
                        placeholder="Minimum 8 characters">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Re-type password">
                </div>
            </div>
        </div>

        <!-- Role & Modular Permissions -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm space-y-5" x-data="{ isSuper: {{ old('is_super_admin') ? 'true' : 'false' }} }">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Access Permissions</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Select which administrative sections this user can access</p>
                </div>
                <div class="flex items-center gap-2" x-show="!isSuper">
                    <button type="button" @click="document.querySelectorAll('.perm-checkbox').forEach(c => c.checked = true)" class="text-xs text-indigo-600 hover:underline font-medium">Select All</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" @click="document.querySelectorAll('.perm-checkbox').forEach(c => c.checked = false)" class="text-xs text-gray-500 hover:underline">Clear All</button>
                </div>
            </div>

            <!-- Super Admin Toggle -->
            <div class="p-4 rounded-lg bg-amber-50/70 border border-amber-200/80">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="is_super_admin" id="is_super_admin" value="1"
                        x-model="isSuper"
                        class="mt-1 rounded border-amber-400 text-amber-600 shadow-sm focus:ring-amber-500">
                    <div>
                        <div class="text-sm font-bold text-amber-950 flex items-center gap-1.5">
                            <span>👑</span> Super Administrator (Full Unrestricted Access)
                        </div>
                        <p class="text-xs text-amber-800/80 mt-0.5">
                            When enabled, this user automatically has permission across all existing and future modules without restriction.
                        </p>
                    </div>
                </label>
            </div>

            <!-- Individual Modular Permissions Grid -->
            <div class="space-y-3" :class="isSuper ? 'opacity-50 pointer-events-none' : ''">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Modular Scopes:</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach ($availablePermissions as $key => $perm)
                        <label class="relative flex items-start p-3.5 rounded-lg border border-gray-200 hover:border-indigo-200 hover:bg-slate-50/50 cursor-pointer transition">
                            <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                class="perm-checkbox mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                @checked(in_array($key, old('permissions', ['categories', 'products'])))>
                            <div class="ml-3">
                                <span class="text-sm font-semibold text-gray-900 flex items-center gap-1.5">
                                    <span>{{ $perm['icon'] }}</span> {{ $perm['label'] }}
                                </span>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $perm['description'] }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('permissions')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-md border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 shadow-sm transition">
                Create Admin User
            </button>
        </div>
    </form>
</div>
@endsection
