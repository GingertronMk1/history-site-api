<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Play extends Model
{
    /** @use HasFactory<\Database\Factories\PlayFactory> */
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'season_id',
        'season_sort',
        'period',
        'venue',
        'playwright',
        'date_start',
        'date_end',
        'summary',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];
}
