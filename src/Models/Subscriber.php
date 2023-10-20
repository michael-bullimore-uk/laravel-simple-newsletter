<?php

namespace MIBU\Newsletter\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

/**
 * @property string $id
 * @property string $email
 * @property Carbon|null $verified_at
 */
class Subscriber extends Model
{
    use HasUlids;

    protected $casts = [
        'verified_at' => 'datetime',
    ];
    protected $guarded = [];

    public function getTable(): string
    {
        return config('newsletter.table_name', parent::getTable());
    }

    // We'll use a scope for this so that we can modify the behaviour if req.
    public function scopeEmail(Builder $query, string $email): void
    {
        $query->where('email', $email);
    }

    public function scopeStale(Builder $query, int $days): void
    {
        $query
            ->whereNull('verified_at')
            ->where('created_at', '<', now()->modify("-{$days} days"));
    }

    public function getVerifyUrl(): string
    {
        return URL::signedRoute('newsletter.verify', [
            'id' => $this->getKey(),
        ]);
    }

    public function getUnsubscribeUrl(): string
    {
        return URL::signedRoute('newsletter.unsubscribe', [
            'id' => $this->getKey(),
        ]);
    }
}
