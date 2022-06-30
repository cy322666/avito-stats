<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsStats extends Model
{
    use HasFactory;

    protected $fillable = [
        'ads_id',
        'date',
        'uniq_views',
        'uniq_contacts',
        'uniq_favorites',
    ];
}
