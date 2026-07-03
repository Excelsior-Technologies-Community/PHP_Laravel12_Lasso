<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildLog extends Model
{
    protected $fillable = [
        'build_id',
        'log_content',
        'log_type'
    ];

    public function build()
    {
        return $this->belongsTo(BuildHistory::class);
    }
}