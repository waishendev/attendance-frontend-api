<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $employee_id
 * @property int $shift_instance_id
 * @property string $status
 */
class ShiftApplication extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'shift_instance_id', 'status'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shiftInstance()
    {
        return $this->belongsTo(ShiftInstance::class);
    }
}
