<?php

namespace App\Http\Controllers;

use App\Models\_Class;
use App\Models\Subject;
use App\Models\TimeTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $classId = $request->query('class_id');

        // Fetch all classes
        $classes = _Class::all();

        // Fetch timetables based on selected class
        if ($classId) {
            $timetables = TimeTable::where('class_id', $classId)->get();
        } else {
            $timetables = TimeTable::all();
        }
        return view('timetables.index', compact('classes', 'timetables', 'classId'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $classId = $request->query('class_id');
        $subjectId = $request->query('subject_id');

        // Fetch all classes
        $classes = _Class::all();

        // Fetch subjects based on selected class
        $subjects = [];
        if ($classId) {
            $subjects = Subject::whereHas('classes', function ($query) use ($classId) {
                $query->where('class_id', $classId);
            })->get();
        }

        // Fetch teachers based on selected subject
        $teachers = [];
        if ($subjectId) {
            // get teachers that also in the table teacher_subject_class
            $teachers = User::whereHas('subjects', function ($query) use ($subjectId) {
                $query->where('subject_id', $subjectId);
            })->get();
        }

        // Fetch the selected subject for display
        $selectedSubject = $subjectId ? Subject::find($subjectId) : null;

        return view('timetables.create', compact('classes', 'subjects', 'teachers', 'classId', 'subjectId', 'selectedSubject'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ]);

        // Extract validated data
        $classId = $validated['class_id'];
        $subjectId = $validated['subject_id'];
        $userId = $validated['user_id'];
        $startTime = $validated['start_time'];
        $endTime = $validated['end_time'];
        $dayOfWeek = $validated['day_of_week'];

        // Fetch the required number of sessions for the class and subject
        $requiredSessions = DB::table('class_subject')
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->value('required_sessions');

        // Fetch the weekly sessions limit for the teacher for the class and subject
        $weeklySessions = DB::table('teacher_subject_class')
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('user_id', $userId)
            ->value('weekly_sessions');

        // Fetch existing assigned sessions for the teacher
        $assignedSessions = DB::table('timetables')
            ->where('user_id', $userId)
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('day_of_week', $dayOfWeek)
            ->get(['start_time', 'end_time']);

        // Calculate total duration of assigned sessions
        $totalAssignedDuration = $assignedSessions->sum(function ($session) {
            return (strtotime($session->end_time) - strtotime($session->start_time)) / 3600; // Convert seconds to hours
        });

        // Calculate duration of the new session
        $newSessionDuration = (strtotime($endTime) - strtotime($startTime)) / 3600; // Convert seconds to hours

        // Check if the new session will exceed the required session limit
        if (($totalAssignedDuration + $newSessionDuration) > $requiredSessions) {
            return redirect()->back()->withErrors(['error' => 'The teacher exceeds the maximum required sessions for this subject in this class.']);
        }

        // Check if the new session will exceed the weekly sessions limit of the teacher
        if (($totalAssignedDuration + $newSessionDuration) > $weeklySessions) {
            return redirect()->back()->withErrors(['error' => 'The teacher exceeds the weekly sessions limit for this subject in this class.']);
        }

        // Convert new session times to timestamps
        $newSessionStart = strtotime($startTime);
        $newSessionEnd = strtotime($endTime);

        // Check for conflicts with existing sessions for the teacher
        foreach ($assignedSessions as $session) {
            $existingStart = strtotime($session->start_time);
            $existingEnd = strtotime($session->end_time);

            if (($newSessionStart < $existingEnd) && ($newSessionEnd > $existingStart)) {
                return redirect()->back()->withErrors(['error' => 'The selected teacher is already assigned at this time.']);
            }
        }

        // Check for conflicts with existing sessions for the class
        $classSessions = DB::table('timetables')
            ->where('class_id', $classId)
            ->where('day_of_week', $dayOfWeek)
            ->get(['start_time', 'end_time']);

        foreach ($classSessions as $session) {
            $existingStart = strtotime($session->start_time);
            $existingEnd = strtotime($session->end_time);

            if (($newSessionStart < $existingEnd) && ($newSessionEnd > $existingStart)) {
                return redirect()->back()->withErrors(['error' => 'The selected class already has a session scheduled at this time.']);
            }
        }

        // Store the new timetable entry
        DB::table('timetables')->insert([
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'user_id' => $userId,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'day_of_week' => $dayOfWeek,
        ]);

        return redirect()->route('timetables.index')->with('success', 'TimeTable created successfully.');
    }    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $timetable = TimeTable::findOrFail($id);
        return view('timetables.show', compact('timetable'));        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $timeTable = TimeTable::findOrFail($id);
        $selectedClass = $request->query('class_id', $timeTable->class_id);
        $selectedSubject = $request->query('subject_id', $timeTable->subject_id);
        $selectedTeacher = $request->query('teacher_id', $timeTable->user_id);
        
        $classes = _Class::all();
        $subjects = [];
        if ($selectedClass) {
            $subjects = Subject::whereHas('classes', function ($query) use ($selectedClass) {
                $query->where('class_id', $selectedClass);
            })->get();
        }
        
        $teachers = [];
        if ($selectedSubject) {
            $teachers = User::whereHas('subjects', function ($query) use ($selectedSubject) {
                $query->where('subject_id', $selectedSubject);
            })->get();
        }

        return view('timetables.edit', compact('classes', 'subjects', 'teachers', 'selectedClass', 'selectedSubject', 'selectedTeacher', 'timeTable'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $timeTable_id)
    {
        $timeTable = TimeTable::findOrFail($timeTable_id);
        // dd($request->all());
        // Validate incoming request data
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required',
            'end_time' => 'required',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ]);

        // Extract validated data
        $classId = $validated['class_id'];
        $subjectId = $validated['subject_id'];
        $userId = $validated['user_id'];
        $startTime = $validated['start_time'];
        $endTime = $validated['end_time'];
        $dayOfWeek = $validated['day_of_week'];

        // Fetch the required number of sessions for the class and subject
        $requiredSessions = DB::table('class_subject')
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->value('required_sessions');

        // Fetch the weekly sessions limit for the teacher for the class and subject
        $weeklySessions = DB::table('teacher_subject_class')
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('user_id', $userId)
            // ->where('id', '!=', $timeTable->id) // Exclude the current session
            ->value('weekly_sessions');

        // Fetch existing assigned sessions for the teacher
        // dd($timeTable->id);
        $assignedSessions = DB::table('timetables')
        ->where('user_id', $userId)
        ->where('class_id', $classId)
        ->where('subject_id', $subjectId)
        ->where('day_of_week', $dayOfWeek)
        ->where('id', '!=', $timeTable->id) // timetable->id is null we should do that where user
        ->get(['start_time', 'end_time']);

        // Calculate total duration of assigned sessions
        $totalAssignedDuration = $assignedSessions->sum(function ($session) {
            return (strtotime($session->end_time) - strtotime($session->start_time)) / 3600; // Convert seconds to hours
        });
        // dd($startTime);
        // Calculate duration of the new session
        $newSessionDuration = (strtotime($endTime) - strtotime($startTime)) / 3600; // Convert seconds to hours
        // dd($totalAssignedDuration);
        // Check if the new session will exceed the required session limit
        if (($totalAssignedDuration + $newSessionDuration) > $requiredSessions) {
            return redirect()->back()->withErrors(['error' => 'The teacher exceeds the maximum required sessions for this subject in this class.']);
        }

        // Check if the new session will exceed the weekly sessions limit of the teacher
        if (($totalAssignedDuration + $newSessionDuration) > $weeklySessions) {
            return redirect()->back()->withErrors(['error' => 'The teacher exceeds the weekly sessions limit for this subject in this class.']);
        }

        // Convert new session times to timestamps
        $newSessionStart = strtotime($startTime);
        $newSessionEnd = strtotime($endTime);

        // Check for conflicts with existing sessions for the teacher
        foreach ($assignedSessions as $session) {
            $existingStart = strtotime($session->start_time);
            $existingEnd = strtotime($session->end_time);

            if (($newSessionStart < $existingEnd) && ($newSessionEnd > $existingStart)) {
                return redirect()->back()->withErrors(['error' => 'The selected teacher is already assigned at this time.']);
            }
        }

        // Check for conflicts with existing sessions for the class
        $classSessions = DB::table('timetables')
            ->where('class_id', $classId)
            ->where('day_of_week', $dayOfWeek)
            ->where('id', '!=', $timeTable->id) // timetable->id is null we should do that where user
            ->get(['start_time', 'end_time']);

        foreach ($classSessions as $session) {
            $existingStart = strtotime($session->start_time);
            $existingEnd = strtotime($session->end_time);

            if (($newSessionStart < $existingEnd) && ($newSessionEnd > $existingStart)) {
                return redirect()->back()->withErrors(['error' => 'The selected class already has a session scheduled at this time.']);
            }
        }

        // Update the timetable
        $timeTable->update($validated);

        // Redirect with success message
        return redirect()->route('timetables.index')->with('success', 'TimeTable updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimeTable $timeTable)
    {
        // Code to delete a timetable
    }
}
