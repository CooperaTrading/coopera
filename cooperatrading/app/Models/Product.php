<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image',
        'description',
        'unit_of_measure',
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
        static::saving(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('storage/'.$this->image))) {
            return asset('storage/'.$this->image);
        }

        $slug = $this->slug ?: Str::slug($this->name);

        if (file_exists(public_path('assets/images/products/'.$slug.'.webp'))) {
            return asset('assets/images/products/'.$slug.'.webp');
        }

        if (file_exists(public_path('assets/images/products/'.$slug.'.jpeg'))) {
            return asset('assets/images/products/'.$slug.'.jpeg');
        }

        if (file_exists(public_path('assets/images/products/'.$slug.'.jpg'))) {
            return asset('assets/images/products/'.$slug.'.jpg');
        }

        if (file_exists(public_path('assets/images/products/'.$slug.'.png'))) {
            return asset('assets/images/products/'.$slug.'.png');
        }

        if (file_exists(public_path('assets/images/products/'.$slug.'.svg'))) {
            return asset('assets/images/products/'.$slug.'.svg');
        }

        return asset('assets/images/product-placeholder.jpg');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
