<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'image_url',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget('sitemap_xml_content'));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('sitemap_xml_content'));
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('storage/'.$this->image))) {
            return asset('storage/'.$this->image);
        }

        $slug = $this->slug ?: Str::slug($this->name);

        if (file_exists(public_path('assets/images/categories/'.$slug.'.webp'))) {
            return asset('assets/images/categories/'.$slug.'.webp');
        }

        if (file_exists(public_path('assets/images/categories/'.$slug.'.jpeg'))) {
            return asset('assets/images/categories/'.$slug.'.jpeg');
        }

        if (file_exists(public_path('assets/images/categories/'.$slug.'.jpg'))) {
            return asset('assets/images/categories/'.$slug.'.jpg');
        }

        if (file_exists(public_path('assets/images/categories/'.$slug.'.png'))) {
            return asset('assets/images/categories/'.$slug.'.png');
        }

        if (file_exists(public_path('assets/images/categories/'.$slug.'.svg'))) {
            return asset('assets/images/categories/'.$slug.'.svg');
        }

        return asset('assets/images/product-placeholder.jpg');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
