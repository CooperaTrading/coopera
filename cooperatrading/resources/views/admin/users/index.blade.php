@extends('layouts.admin')

@section('title', 'Admin Users & Access Control')

@section('header', 'Admin Users')
@section('subheader', 'Manage administrator accounts and configure their module access permissions')

@section('actions')
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 shadow-sm transition">
        <span>+</span> New Admin User
    </a>
@endsection

@section('content')
<div class="space-y-4">
    @if (session('error'))
        <div class="rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">User</th>
                        <th class="px-5 py-3.5">Role</th>
                        <th class="px-5 py-3.5">Granted Access Scopes</th>
                        <th class="px-5 py-3.5">Added</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- User Name & Email -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-800 text-white font-semibold flex items-center justify-center text-xs shrink-0 shadow-inner">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-900 flex items-center gap-2">
                                            <span>{{ $user->name }}</span>
                                            @if ($user->id === auth()->id())
                                                <span class="text-[10px] bg-emerald-100 text-emerald-800 px-1.5 py-0.2 rounded font-medium">You</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 font-mono">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($user->isSuperAdmin())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-300 shadow-sm">
                                        <span>👑</span> Super Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        <span>🛡️</span> Admin
                                    </span>
                                @endif
                            </td>

                            <!-- Access Permissions -->
                            <td class="px-5 py-4">
                                @if ($user->isSuperAdmin())
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 bg-amber-50/80 px-2 py-0.5 rounded border border-amber-200">
                                        ⚡ Full Administrative Access (All Modules)
                                    </span>
                                @elseif (is_array($user->permissions) && count($user->permissions) > 0)
                                    <div class="flex flex-wrap gap-1.5 max-w-md">
                                        @foreach ($user->permissions as $permKey)
                                            @php $p = $availablePermissions[$permKey] ?? null; @endphp
                                            @if ($p)
                                                <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200" title="{{ $p['description'] }}">
                                                    <span>{{ $p['icon'] }}</span> {{ $p['label'] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">No modular permissions assigned</span>
                                @endif
                            </td>

                            <!-- Added Date -->
                            <td class="px-5 py-4 text-xs text-gray-500 whitespace-nowrap">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : '—' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right whitespace-nowrap text-xs">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="px-2.5 py-1 rounded text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 font-medium transition border border-indigo-200">
                                        Edit Access
                                    </a>

                                    @if ($user->id !== auth()->id() && $user->email !== 'tsedey.yayeh@cooperatrading.com')
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete admin user \'{{ $user->name }}\'?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 rounded text-red-600 hover:text-red-900 hover:bg-red-50 font-medium transition border border-red-200">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-500 text-sm">
                                No admin users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="px-5 py-3 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
