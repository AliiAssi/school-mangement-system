<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = ['name', 'description', 'abbreviation']; 

    public function classes()
    {
        return $this->belongsToMany(_Class::class, 'class_subject', 'subject_id', 'class_id')
                    ->withPivot('required_sessions');
    }

    // Define relationship with teachers through the pivot table
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subject_class', 'subject_id', 'user_id')
                    ->withPivot('weekly_sessions')
                    ->where('isAdmin', 0); // Only teachers
    }
    public function timetables()
    {
        return $this->hasMany(TimeTable::class, 'subject_id');
    }
}
