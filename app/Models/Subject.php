<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = ['name', 'description','abbreviation']; 

    // Define relationships
    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'subject_id');
    }

    public function classes()
    {
        return $this->belongsToMany(_Class::class, 'class_subject', 'subject_id', 'class_id')
                ->withPivot('required_sessions')
                ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'teacher_subject_class')
                    ->withPivot('weekly_sessions');
    }
}
