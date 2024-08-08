<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    // Define the table associated with the model if it does not follow Laravel's conventions
    protected $table = 'timetable';

    // Define the fillable attributes to allow mass assignment
    protected $fillable = ['class_id', 'user_id', 'subject_id', 'start_time', 'end_time', 'day_of_week'];

    // Define relationships
    public function _class()
    {
        return $this->belongsTo(_Class::class, 'class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
