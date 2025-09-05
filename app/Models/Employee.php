<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
    ];

    public function logs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
