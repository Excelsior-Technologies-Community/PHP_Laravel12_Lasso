<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildHistory extends Model
{
    protected $fillable = [
        'version',
        'asset_count',
        'status',
        'published_at',
        'environment',
        'deployed_by',
        'notes',
        'is_active',
        'build_log',
        'backup_path'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function logs()
    {
        return $this->hasMany(BuildLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}