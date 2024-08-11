<?php

namespace App\Http\Controllers;

use App\Models\_Class;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserSubjectClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    // Display a listing of the teachers.
    public function index()
    {
        $teachers = User::where('isAdmin', 0)->get();
        return view('teachers.index', compact('teachers'));
    }

    // Show the form for creating a new teacher.
    public function create()
    {
        // Fetch all subjects
        $subjects = Subject::with('classes')->get();
        // Fetch all classes
        $classes = _Class::all();

        return view('teachers.create', [
            'formAction' => route('teachers.store'),
            'subjects' => $subjects,
            'classes' => $classes,
        ]);
    }


    // Store a newly created teacher in storage.
    public function store(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'subjects' => 'required|array',
            'subjects.*.grades' => 'required|array',
            'subjects.*.grades.*.selected' => 'sometimes|boolean',
            'subjects.*.grades.*.weekly_sessions' => 'sometimes|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Start a transaction
        DB::beginTransaction();

        try {
            // Create the new user
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'isAdmin' => 0, // Default value for teacher
            ]);

            // Process subjects and grades
            foreach ($request->input('subjects') as $subjectId => $subjectData) {
                // Check if any grade is selected
                $grades = $subjectData['grades'] ?? [];
                $hasSelectedGrade = collect($grades)->contains('selected', '1');

                if ($hasSelectedGrade) {
                    foreach ($grades as $gradeId => $gradeData) {
                        if (isset($gradeData['selected']) && $gradeData['selected'] == '1') {
                            // Insert record into pivot table
                            DB::table('teacher_subject_class')->insert([
                                'class_id' => $gradeId,
                                'user_id' => $user->id,
                                'subject_id' => $subjectId,
                                'weekly_sessions' => $gradeData['weekly_sessions'] ?? 0,
                            ]);
                        }
                    }
                }
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('teachers.index')->with('success', 'Teacher added successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'An error occurred while adding the teacher.'])->withInput();
        }
    }


    // Display the specified teacher.
    public function show($id)
    {
        // Fetch teacher with subjects and their associated classes
        $teacher = User::with(['subjects' => function ($query) {
            $query->with('classes');
        }])->findOrFail($id);

        return view('teachers.show', [
            'teacher' => $teacher,
        ]);
    }


    public function edit($id)
    {
        $teacher = User::findOrFail($id);
        $subjects = Subject::with('classes')->get(); // Ensure you load the classes for each subject
        $teacherSubjects = [];

        foreach ($subjects as $subject) {
            foreach ($subject->classes as $class) {
                // Fetch the weekly sessions from the teacher_subject_class table
                $record = UserSubjectClass::where('user_id', $id)
                    ->where('subject_id', $subject->id)
                    ->where('class_id', $class->id)
                    ->first();

                if ($record) {
                    $teacherSubjects[$subject->id]['grades'][$class->id] = [
                        'selected' => $record->selected,
                        'weekly_sessions' => $record->weekly_sessions,
                    ];
                }
            }
        }

        return view('teachers.edit', compact('teacher', 'subjects', 'teacherSubjects'));
    }


    // Update the specified teacher in storage.
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'subjects.*.grades.*.selected' => 'boolean',
            'subjects.*.grades.*.weekly_sessions' => 'nullable|integer|min:0',
        ]);

        $teacher = User::findOrFail($id);

        // Update teacher details
        $teacher->name = $request->input('name');
        $teacher->email = $request->input('email');

        if ($request->filled('password')) {
            $teacher->password = bcrypt($request->input('password'));
        }

        $teacher->save();

        // Clear existing subject assignments
        $teacher->subjects()->detach();

        // Update subject assignments
        foreach ($request->input('subjects', []) as $subjectId => $grades) {
            foreach ($grades['grades'] as $classId => $gradeData) {
                if (isset($gradeData['selected']) && $gradeData['selected']) {
                    $teacher->subjects()->attach($subjectId, [
                        'class_id' => $classId,
                        'weekly_sessions' => $gradeData['weekly_sessions'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }


    // Remove the specified teacher from storage.
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
