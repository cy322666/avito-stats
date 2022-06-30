<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ads extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'ads_id',
        'price',
        'category_name',
        'title',
        'status',
        'url',
        'stats_updated_at',
        'calls_updated_at',
        'services_updated_at',
    ];

    public function stats(): HasMany
    {
        return $this->hasMany(AdsStats::class, 'ads_id', 'ads_id');
    }
}
