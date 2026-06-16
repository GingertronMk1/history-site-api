<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    //
    use HasUuids;
    use SoftDeletes;
    protected $fillable = [
        'name',
    ];
}
