<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTable extends Model
{
    use HasFactory;

    protected $table = 'timetables';

    protected $fillable = [
        'class_id',
        'user_id',
        'subject_id',
        'start_time',
        'end_time',
        'day_of_week',
    ];

    public function class()
    {
        return $this->belongsTo(_Class::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    
}
