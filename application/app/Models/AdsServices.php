<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsServices extends Model
{
    use HasFactory;

    protected $fillable = [
        'finish_time',
        'schedule',
        'vas_id',
        'ads_id',
    ];
}
