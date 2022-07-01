<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsCalls extends Model
{
    use HasFactory;

    protected $table = 'ads_calls';

    protected $fillable = [
        "answered",
        "calls",
        "date",
        "new",
        "new_answered",
        'ads_id',
    ];
}
