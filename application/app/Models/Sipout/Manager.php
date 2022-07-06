<?php

namespace App\Models\Sipout;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    protected $table = 'sipout_managers';

    protected $fillable = [
        "number",
        "descr",
        "sip_login",
        "sip_password",
        "aon",
        "email",
        "pickup_groups",
        "type",
    ];
}
