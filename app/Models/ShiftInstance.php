<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $project_id
 * @property int $location_id
 * @property \DateTimeInterface $start_at
 * @property \DateTimeInterface $end_at
 */
class ShiftInstance extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'location_id', 'start_at', 'end_at'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function applications()
    {
        return $this->hasMany(ShiftApplication::class);
    }
}
