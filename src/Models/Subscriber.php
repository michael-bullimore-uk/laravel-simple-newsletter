<?php

namespace MIBU\Newsletter\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $casts = [
        'verified_at' => 'datetime',
    ];
    protected $guarded = [];

    public function getTable(): string
    {
        return config('newsletter.table_name', parent::getTable());
    }
}
