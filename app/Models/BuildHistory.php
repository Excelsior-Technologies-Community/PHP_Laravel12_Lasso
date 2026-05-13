<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildHistory extends Model
{
    protected $fillable = [
        'version',
        'asset_count',
        'status',
        'published_at'
    ];
}