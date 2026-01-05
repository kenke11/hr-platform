<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'location',
        'employment_type',
        'status',
        'published_at',
        'expiration_date',
        'is_active',
    ];

    protected $casts = [
        'published_at'   => 'datetime',
        'expiration_date'=> 'date',
        'is_active'      => 'boolean',
    ];

    /* ==========================================================
     | Scopes
     |========================================================== */

    /**
     * Published vacancies (status + published_at)
     */
    public function scopePublished($query)
    {
        return $query
            ->where('status', 'published')
            ->whereNotNull('published_at');
    }

    /**
     * Draft vacancies
     */
    public function scopeDraft($query)
    {
        return $query
            ->where('status', 'draft')
            ->whereNull('published_at');
    }

    /**
     * Active vacancies (published + not expired)
     */
    public function scopeActive($query)
    {
        return $query
            ->published()
            ->where(function ($q) {
                $q->whereNull('expiration_date')
                    ->orWhere('expiration_date', '>=', now()->toDateString());
            });
    }

    /* ==========================================================
     | State helpers (DOMAIN LOGIC)
     |========================================================== */

    /**
     * Publish vacancy
     */
    public function publish(): void
    {
        $this->update([
            'status'        => 'published',
            'published_at'  => now(),
        ]);
    }

    /**
     * Unpublish vacancy (back to draft)
     */
    public function unpublish(): void
    {
        $this->update([
            'status'        => 'draft',
            'published_at'  => null,
        ]);
    }

    /**
     * Check if vacancy is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published'
            && ! is_null($this->published_at);
    }

    /**
     * Check if vacancy is expired
     */
    public function isExpired(): bool
    {
        return $this->expiration_date !== null
            && $this->expiration_date->isPast();
    }

    /**
     * Check if vacancy is visible publicly
     */
    public function isVisible(): bool
    {
        return $this->isPublished() && ! $this->isExpired();
    }

    /* ==========================================================
     | Relations
     |========================================================== */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
