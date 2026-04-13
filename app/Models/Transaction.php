<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'type',
        'amount',
        'transaction_date',
        'transaction_bs',
        'payment_method',
        'reference_no',
        'notes',
        'email_notified',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transaction_date' => 'date',
            'email_notified' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, fn (Builder $builder, string $search) => $builder->where(function (Builder $inner) use ($search) {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhere('reference_no', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            }))
            ->when($filters['type'] ?? null, fn (Builder $builder, string $type) => $builder->where('type', $type))
            ->when($filters['category_id'] ?? null, fn (Builder $builder, int|string $categoryId) => $builder->where('category_id', $categoryId))
            ->when($filters['date_from'] ?? null, fn (Builder $builder, string $date) => $builder->whereDate('transaction_date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn (Builder $builder, string $date) => $builder->whereDate('transaction_date', '<=', $date))
            ->when($filters['from_bs'] ?? null, function (Builder $builder, string $date) {
                if ($adDate = ad_date_from_bs($date)) {
                    $builder->whereDate('transaction_date', '>=', $adDate);
                }
            })
            ->when($filters['to_bs'] ?? null, function (Builder $builder, string $date) {
                if ($adDate = ad_date_from_bs($date)) {
                    $builder->whereDate('transaction_date', '<=', $adDate);
                }
            });
    }
}
