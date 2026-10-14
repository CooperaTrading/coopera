<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUserRequest;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $users = User::where('is_admin', true)
            ->orderByDesc('is_super_admin')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
            'availablePermissions' => User::availablePermissions(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'availablePermissions' => User::availablePermissions(),
        ]);
    }

    public function store(StoreAdminUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $isSuperAdmin = ! empty($validated['is_super_admin']);
        $permissions = $validated['permissions'] ?? [];

        if ($isSuperAdmin) {
            $permissions = array_keys(User::availablePermissions());
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => true,
            'is_super_admin' => $isSuperAdmin,
            'permissions' => $permissions,
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Admin user '{$validated['name']}' created successfully.");
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'availablePermissions' => User::availablePermissions(),
        ]);
    }

    public function update(UpdateAdminUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $isPrimarySuperAdmin = $user->email === 'tsedey.yayeh@cooperatrading.com' ||
            $user->email === config('mail.admin_email', env('ADMIN_EMAIL'));

        $data = [
            'name' => $validated['name'],
            'email' => $isPrimarySuperAdmin ? $user->email : $validated['email'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($isPrimarySuperAdmin) {
            $data['is_super_admin'] = true;
            $data['permissions'] = array_keys(User::availablePermissions());
        } else {
            $isSuperAdmin = ! empty($validated['is_super_admin']);
            $data['is_super_admin'] = $isSuperAdmin;
            $data['permissions'] = $isSuperAdmin
                ? array_keys(User::availablePermissions())
                : ($validated['permissions'] ?? []);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Admin user '{$user->name}' updated successfully.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->email === 'tsedey.yayeh@cooperatrading.com' || $user->email === config('mail.admin_email', env('ADMIN_EMAIL'))) {
            return back()->with('error', 'The primary super administrator account cannot be deleted.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Admin user '{$name}' has been deleted.");
    }
}
