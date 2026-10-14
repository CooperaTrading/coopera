<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'logo_url',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo && file_exists(public_path('storage/' . $this->logo))) {
            return asset('storage/' . $this->logo);
        }

        $slug = Str::slug($this->name);

        if (file_exists(public_path('assets/images/partners/' . $slug . '.svg'))) {
            return asset('assets/images/partners/' . $slug . '.svg');
        }

        if (file_exists(public_path('assets/images/partners/' . $slug . '.webp'))) {
            return asset('assets/images/partners/' . $slug . '.webp');
        }

        if (file_exists(public_path('assets/images/partners/' . $slug . '.png'))) {
            return asset('assets/images/partners/' . $slug . '.png');
        }

        if (file_exists(public_path('assets/images/partners/' . $slug . '.jpg'))) {
            return asset('assets/images/partners/' . $slug . '.jpg');
        }

        return asset('assets/images/product-placeholder.jpg');
    }
}
