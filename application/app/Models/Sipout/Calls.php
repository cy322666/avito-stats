<?php

namespace App\Models\Sipout;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calls extends Model
{
    use HasFactory;

    protected $table = 'sipout_calls';

    protected $fillable = [
        "date",
        "cnam",
        "caller",
        "called",
        "duration" ,
        "direction",
        "type",
        "answer",
        "note_cnt",
        "callid",
        "ts",
    ];
}
