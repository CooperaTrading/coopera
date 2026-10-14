<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Remove legacy default admin accounts if they exist
        User::whereIn('email', ['admin@cooperatrading.com', 'info@cooperatrading.com'])->delete();

        User::updateOrCreate(
            ['email' => 'tsedey.yayeh@cooperatrading.com'],
            [
                'name' => 'Tsedey Yayeh',
                'password' => Hash::make('P@ssw0rdcoop2026'),
                'is_admin' => true,
                'is_super_admin' => true,
                'permissions' => array_keys(User::availablePermissions()),
                'email_verified_at' => now(),
            ]
        );
    }
}
