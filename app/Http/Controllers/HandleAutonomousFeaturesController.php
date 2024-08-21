<?php

namespace App\Http\Controllers;

use App\Models\_Class;
use App\Models\TimeTable;
use App\Models\UserSubjectClass;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class HandleAutonomousFeaturesController extends Controller
{
    private $startHour;
    private $endHour;
    private $sessionDuration;
    private $breakStart;
    private $breakEnd;
    private $delayBetweenSessions;
    private $daysOfWeek;
    
    public function __construct()
    {
        $this->initializeSettings();
    }

    private function initializeSettings()
    {
        $settings = $this->getSettings();

        $this->startHour = Carbon::createFromFormat('H:i', $settings['start_time']);
        $this->endHour = Carbon::createFromFormat('H:i', $settings['end_time']);
        $this->sessionDuration = (int)$settings['session_duration'];
        $this->breakStart = Carbon::createFromFormat('H:i', $settings['break_start']);
        $this->breakEnd = Carbon::createFromFormat('H:i', $settings['break_end']);
        $this->delayBetweenSessions = (int)$settings['delay_between_sessions'];
        $this->daysOfWeek = $this->filterDaysOfWeek($settings['off_days']);
    }

    private function getSettings()
    {
        $filePath = 'util/settings.txt';
        $settings = [
            'start_time' => '08:00',
            'end_time' => '17:00',
            'session_duration' => '1',
            'delay_between_sessions' => '5',
            'break_start' => '12:00',
            'break_end' => '12:30',
            'off_days' => ['Saturday', 'Sunday']
        ];

        if (Storage::disk('local')->exists($filePath)) {
            $fileContent = Storage::disk('local')->get($filePath);
            $lines = array_filter(array_map('trim', explode("\n", $fileContent)));

            foreach ($lines as $line) {
                list($key, $value) = explode(': ', $line, 2) + [null, null];
                if ($key && $value) {
                    $settings[$key] = ($key === 'off_days') ? explode(', ', $value) : $value;
                }
            }
        }

        return $settings;
    }

    private function filterDaysOfWeek($offDays)
    {
        $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return array_diff($allDays, array_map('ucfirst', array_map('strtolower', $offDays)));
    }

    private function isSlotAvailable($classId, $dayOfWeek, Carbon $startTime, Carbon $endTime)
    {
        return !TimeTable::where('class_id', $classId)
            ->where('day_of_week', $dayOfWeek)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($subQuery) use ($startTime, $endTime) {
                        $subQuery->where('start_time', '<', $startTime)
                            ->where('end_time', '>', $endTime);
                    });
            })
            ->exists();
    }

    private function isTeacherAvailable($teacherId, $dayOfWeek, Carbon $startTime, Carbon $endTime)
    {
        return !TimeTable::where('user_id', $teacherId)
            ->where('day_of_week', $dayOfWeek)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($subQuery) use ($startTime, $endTime) {
                        $subQuery->where('start_time', '<', $startTime)
                            ->where('end_time', '>', $endTime);
                    });
            })
            ->exists();
    }

    private function getTeacherWeeklySessions($teacherId, $classId, $subjectId)
    {
        return TimeTable::where('user_id', $teacherId)
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->count();
    }

    private function getAvailableTimeSlots(Carbon $start, Carbon $end, Carbon $breakStart, Carbon $breakEnd, $sessionDuration, $delay)
    {
        $slots = [];
        $current = $start->copy();

        while ($current->lt($end)) {
            $slotEnd = $current->copy()->addHours($sessionDuration);

            if ($slotEnd->gt($end)) {
                break;
            }

            if (!($slotEnd->gt($breakStart) && $current->lt($breakEnd))) {
                $slots[] = ['start' => $current->copy(), 'end' => $slotEnd->copy()];
            }

            $current->addHours($sessionDuration)->addMinutes($delay);
        }

        return $slots;
    }

    public function handleTimeTableGeneration()
    {
        //delete all rows
        TimeTable::truncate();
        // implement the process
        $classes = _Class::all();

        foreach ($classes as $class) {
            $subjects = $class->subjects;

            foreach ($subjects as $subject) {
                $requiredSessions = $subject->pivot->required_sessions;
                $teachers = $subject->teachers()->wherePivot('class_id', $class->id)->get();

                foreach ($this->daysOfWeek as $dayOfWeek) {
                    foreach ($teachers as $teacher) {
                        $timeSlots = $this->getAvailableTimeSlots($this->startHour, $this->endHour, $this->breakStart, $this->breakEnd, $this->sessionDuration, $this->delayBetweenSessions);

                        foreach ($timeSlots as $slot) {
                            if ($requiredSessions <= 0) {
                                break 2; // Move to next subject once required sessions are scheduled
                            }

                            // Check if the teacher has reached their weekly session limit for this subject in this class
                            $weeklySessions = $this->getTeacherWeeklySessions($teacher->id, $class->id, $subject->id);
                            if ($weeklySessions >= $teacher->pivot->weekly_sessions) {
                                continue; // Skip if the teacher has completed their weekly sessions
                            }

                            if ($this->isSlotAvailable($class->id, $dayOfWeek, $slot['start'], $slot['end']) && $this->isTeacherAvailable($teacher->id, $dayOfWeek, $slot['start'], $slot['end'])) {
                                TimeTable::create([
                                    'class_id' => $class->id,
                                    'user_id' => $teacher->id,
                                    'subject_id' => $subject->id,
                                    'start_time' => $slot['start']->format('H:i'),
                                    'end_time' => $slot['end']->format('H:i'),
                                    'day_of_week' => $dayOfWeek,
                                ]);

                                $requiredSessions--;

                                // Move to the next time slot
                                break;
                            }
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Timetables generated successfully.');
    }

    public function exportTimetablesToPdf()
    {
        $timetables = TimeTable::all();
        $classes = _Class::all();

        $pdf = Pdf::loadView('timetables.timetables_pdf', ['timetables' => $timetables, 'classes' => $classes])
            ->setPaper('a4', 'landscape');

        return $pdf->download('timetables.pdf');
    }
}
