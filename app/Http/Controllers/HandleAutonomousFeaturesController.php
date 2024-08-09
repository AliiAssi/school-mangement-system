<?php

namespace App\Http\Controllers;

use App\Models\_Class;
use App\Models\Subject;
use App\Models\TimeTable;
use App\Models\UserSubjectClass;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class HandleAutonomousFeaturesController extends Controller
{
    public function handleTimeTableGeneration()
    {
        $classes = _Class::all();
        $startingGeneratingHour = 8;  // 8 AM
        $endingGeneratingHour = 17;   // 5 PM
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $errors = [];

        foreach ($classes as $class) {
            // Fetch all subjects for the current class
            $subjects = Subject::whereIn('id', function ($query) use ($class) {
                $query->select('subject_id')
                    ->from('class_subject')
                    ->where('class_id', $class->id);
            })->get();

            foreach ($subjects as $subject) {
                // Fetch the number of sessions required for this subject in this class
                $requiredSessions = DB::table('class_subject')
                    ->where('class_id', $class->id)
                    ->where('subject_id', $subject->id)
                    ->value('required_sessions');

                // Fetch available teachers for this subject
                $availableTeachers = UserSubjectClass::where('class_id', $class->id)
                    ->where('subject_id', $subject->id)
                    ->get();

                $sessionsAllocated = 0;
                $teachersSessions = [];
                $teachersMaxSessions = [];

                foreach ($availableTeachers as $teacher) {
                    $teachersSessions[$teacher->user_id] = 0; // Initialize allocated sessions for this teacher
                    $teachersMaxSessions[$teacher->user_id] = $teacher->weekly_sessions;
                }

                foreach ($availableTeachers as $teacher) {
                    $sessionsPerTeacher = 0;

                    // Try to allocate sessions
                    foreach ($daysOfWeek as $dayOfWeek) {
                        if ($sessionsAllocated >= $requiredSessions) break;

                        // Define possible time slots and check availability
                        $timeSlot = $this->getAvailableTimeSlot($startingGeneratingHour, $endingGeneratingHour, $class->id, $subject->id, $dayOfWeek);

                        if ($timeSlot) {
                            $sessionDuration = $this->calculateSessionDuration($timeSlot['start_time'], $timeSlot['end_time']);

                            // Check if adding this session would exceed the teacher's weekly sessions limit
                            if (($teachersSessions[$teacher->user_id] + $sessionDuration) <= $teachersMaxSessions[$teacher->user_id]) {
                                // Check if the teacher already has sessions at this time
                                $conflictExists = DB::table('timetables')
                                    ->where('user_id', $teacher->user_id)
                                    ->where('day_of_week', $dayOfWeek)
                                    ->where(function ($query) use ($timeSlot) {
                                        $query->whereBetween('start_time', [$timeSlot['start_time'], $timeSlot['end_time']])
                                            ->orWhereBetween('end_time', [$timeSlot['start_time'], $timeSlot['end_time']])
                                            ->orWhere(function ($query) use ($timeSlot) {
                                                $query->where('start_time', '<=', $timeSlot['start_time'])
                                                    ->where('end_time', '>=', $timeSlot['end_time']);
                                            });
                                    })
                                    ->exists();

                                // Ensure the session does not exceed the maximum required sessions
                                $teacherSubjectSessions = DB::table('timetables')
                                    ->where('user_id', $teacher->user_id)
                                    ->where('class_id', $class->id)
                                    ->where('subject_id', $subject->id)
                                    ->count();

                                if (!$conflictExists && ($teacherSubjectSessions < $requiredSessions)) {
                                    // Save to timetable
                                    DB::table('timetables')->insert([
                                        'class_id' => $class->id,
                                        'user_id' => $teacher->user_id,
                                        'subject_id' => $subject->id,
                                        'start_time' => $timeSlot['start_time'],
                                        'end_time' => $timeSlot['end_time'],
                                        'day_of_week' => $dayOfWeek,
                                    ]);

                                    $sessionsAllocated++;
                                    $sessionsPerTeacher++;
                                    $teachersSessions[$teacher->user_id] += $sessionDuration; // Update allocated hours
                                }
                            }
                        }
                    }
                }

                // Check if no sessions were allocated
                if ($sessionsAllocated < $requiredSessions) {
                    $errors[] = "Not enough available slots for subject {$subject->name} in class {$class->name}.";
                }
            }
        }

        if (empty($errors)) {
            $classes = _Class::all();
            $timetables = TimeTable::all();

            return redirect('/timetables')
                ->with('status', 'Timetable generation completed successfully!')
                ->with('classes', $classes)
                ->with('timetables', $timetables);
        } else {
            return redirect('/timetables')
                ->withErrors($errors)
                ->withInput();
        }
    }

    private function getAvailableTimeSlot($startingHour, $endingHour, $classId, $subjectId, $dayOfWeek)
    {
        // Define possible time slots
        for ($hour = $startingHour; $hour < $endingHour; $hour++) {
            $startTime = sprintf('%02d:00:00', $hour);
            $endTime = sprintf('%02d:00:00', $hour + 1);

            // Check if this time slot is available
            $exists = DB::table('timetables')
                ->where('class_id', $classId)
                ->where('subject_id', $subjectId)
                ->where('day_of_week', $dayOfWeek)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->whereBetween('start_time', [$startTime, $endTime])
                            ->orWhereBetween('end_time', [$startTime, $endTime]);
                    })
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                        });
                })
                ->exists();

            if (!$exists) {
                return ['start_time' => $startTime, 'end_time' => $endTime];
            }
        }

        return null;  // No available time slot found
    }

    private function calculateSessionDuration($startTime, $endTime)
    {
        $start = \Carbon\Carbon::createFromFormat('H:i:s', $startTime);
        $end = \Carbon\Carbon::createFromFormat('H:i:s', $endTime);

        return $end->diffInHours($start);
    }
    public function exportTimetablesToPdf()
    {
        // Retrieve all timetables
        // $timetables = TimeTable::with(['class', 'subject', 'teacher'])->get();
        $timetables = TimeTable::all();
        $classes = _Class::all();

        // Generate PDF from view
        $pdf = Pdf::loadView('timetables.timetables_pdf', ['timetables' => $timetables, 'classes' => $classes])
        ->setPaper('a4', 'landscape');

        // Download the generated PDF
        return $pdf->download('timetables.pdf');
    }
}
