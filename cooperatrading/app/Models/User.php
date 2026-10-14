<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_super_admin',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_super_admin' => 'boolean',
            'permissions' => 'array',
        ];
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isSuperAdmin(): bool
    {
        return (bool) (
            $this->is_super_admin ||
            $this->email === 'tsedey.yayeh@cooperatrading.com' ||
            $this->email === config('mail.admin_email', env('ADMIN_EMAIL'))
        );
    }

    public function hasPermission(string $permission): bool
    {
        if (! $this->isAdmin()) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        $perms = $this->permissions ?? [];

        return in_array($permission, $perms, true) || in_array('*', $perms, true);
    }

    public static function availablePermissions(): array
    {
        return [
            'categories' => [
                'label' => 'Categories Management',
                'description' => 'Create, edit, toggle visibility, and manage product categories.',
                'icon' => '📂',
            ],
            'products' => [
                'label' => 'Products Management',
                'description' => 'Add, edit, upload product specs, and toggle active status.',
                'icon' => '📦',
            ],
            'partners' => [
                'label' => 'Partners Management',
                'description' => 'Manage international supply partners and brand logos.',
                'icon' => '🤝',
            ],
            'hero-slides' => [
                'label' => 'Hero Slides Management',
                'description' => 'Upload, reorder, edit, and toggle the home page hero carousel.',
                'icon' => '🖼️',
            ],
            'quotes' => [
                'label' => 'Quote Requests & Proformas',
                'description' => 'Review customer quote inquiries and issue official proforma invoices.',
                'icon' => '📨',
            ],
            'templates' => [
                'label' => 'Proforma Invoice Templates',
                'description' => 'Configure banking details, payment terms, delivery terms, and signatures.',
                'icon' => '🎛️',
            ],
            'users' => [
                'label' => 'Admin Users & Access Control',
                'description' => 'Create admin accounts and grant specific permissions.',
                'icon' => '👥',
            ],
        ];
    }
}
