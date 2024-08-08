<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class _Class extends Model
{
    // Define the table associated with the model if it does not follow Laravel's conventions
    protected $table = 'classes';

    // Define the fillable attributes to allow mass assignment
    protected $fillable = ['name', 'grade'];

    // Define relationships
    public function timetable()
    {
        return $this->hasMany(Timetable::class, 'class_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject')
                    ->withPivot('required_sessions');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subject_class')
                    ->withPivot('weekly_sessions');
    }
}
