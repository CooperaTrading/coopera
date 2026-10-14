<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proforma extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_request_id',
        'proforma_number',
        'issue_date',
        'validity_date',
        'payment_terms',
        'delivery_time',
        'bank_details',
        'notes',
        'subtotal',
        'vat',
        'total',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'validity_date' => 'date',
        'subtotal' => 'decimal:2',
        'vat' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function quoteRequest(): BelongsTo
    {
        return $this->belongsTo(QuoteRequest::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProformaItem::class);
    }

    public static function generateNextNumber(): string
    {
        $prefix = 'PROF-';
        $pad = 4;
        $driver = static::query()->getConnection()->getDriverName();

        $maxSql = match ($driver) {
            'mysql', 'mariadb' => "MAX(CAST(SUBSTRING(proforma_number, " . (strlen($prefix) + 1) . ") AS UNSIGNED))",
            'pgsql' => "MAX(CAST(SUBSTRING(proforma_number FROM " . (strlen($prefix) + 1) . ") AS INTEGER))",
            default => "MAX(CAST(SUBSTR(proforma_number, " . (strlen($prefix) + 1) . ") AS INTEGER))",
        };

        $latest = static::query()
            ->where('proforma_number', 'like', $prefix . '%')
            ->selectRaw("$maxSql as max_num")
            ->value('max_num');

        $next = $latest ? ((int) $latest) + 1 : 1;
        return $prefix . str_pad((string) $next, $pad, '0', STR_PAD_LEFT);
    }
}
