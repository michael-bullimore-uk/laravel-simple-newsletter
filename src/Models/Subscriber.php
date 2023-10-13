<?php

namespace MIBU\Newsletter\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    protected function token(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Hash::make($value),
        );
    }

    public function isValidToken($plainTextToken): bool
    {
        return Hash::check($plainTextToken, $this->token);
    }

    public function scopeEmail(Builder $query, string $email): void
    {
        $query->where('email', $email);
    }
}
