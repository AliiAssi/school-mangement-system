<?php

namespace App\Http\Controllers;

use App\Models\_Class;
use App\Models\Subject;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    // Display a listing of the subjects
    public function index()
    {
        // Retrieve all subjects with their associated classes and sessions
        $subjects = Subject::with('classes')->paginate(4);

        return view('subjects.index', compact('subjects'));
    }
    public function show($id)
    {
        // Load the subject with related classes and teachers, ensuring teachers are distinct
        $subject = Subject::with(['classes','teachers'])->findOrFail($id);

        // Retrieve distinct teachers
        $distinctTeachers = $subject->teachers->unique('id');
        $distinctClasses = $subject->classes->unique('id');
        return view('subjects.show', [
            'subject' => $subject,
            'teachers' => $distinctTeachers
        ]);
    }

    // Show the form for creating a new subject
    public function create()
    {
        $classes = _Class::all(); // Retrieve all classes
        return view('subjects.create', compact('classes'));
    }

    // Show the form for editing the specified subject
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $classes = _Class::all(); // Retrieve all classes
        return view('subjects.edit', compact('subject', 'classes'));
    }

    // Store a newly created subject in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:10',
            'description' => 'required|string',
            'classes' => 'required|array',
            'classes.*.id' => 'nullable|exists:classes,id',
            'classes.*.sessions_required' => 'nullable|integer|min:0',
        ]);
    
        // Start a database transaction
        DB::beginTransaction();
    
        try {
            // Create the subject
            $subject = Subject::create([
                'name' => $request->input('name'),
                'abbreviation' => $request->input('abbreviation'),
                'description' => $request->input('description'),
            ]);
    
            $insertData = [];
    
            foreach ($request->input('classes', []) as $class) {
                if (isset($class['id']) && isset($class['sessions_required'])) {
                    $insertData[] = [
                        'class_id' => $class['id'],
                        'subject_id' => $subject->id,
                        'required_sessions' => $class['sessions_required']
                    ];
                }
            }
    
            if (!empty($insertData)) {
                DB::table('class_subject')->insert($insertData);
            } else {
                throw new \Exception('At least one session is required.');
            }
    
            // Commit the transaction
            DB::commit();
    
            return redirect()->route('subjects.index')
                             ->with('success', 'Subject created successfully.');
    
        } catch (\Exception $e) {
            // Rollback the transaction if there is an error
            DB::rollBack();
    
            return redirect()->back()
                             ->withInput()
                             ->withErrors(['classes' => $e->getMessage()]);
        }
    }
    


    // Update the specified subject in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:10',
            'description' => 'required|string',
            'classes' => 'required|array',
            'classes.*.id' => 'nullable|exists:classes,id',
            'classes.*.sessions_required' => 'nullable|integer|min:0',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update([
            'name' => $request->input('name'),
            'abbreviation' => $request->input('abbreviation'),
            'description' => $request->input('description'),
        ]);

        $classes = $request->input('classes', []);

        // Prepare the data for syncing
        $syncData = [];
        foreach ($classes as $class) {
            if (isset($class['id']) && isset($class['sessions_required'])) {
                $syncData[$class['id']] = ['required_sessions' => $class['sessions_required']];
            }
        }

        // Sync classes and sessions with the subject
        $subject->classes()->sync($syncData);

        return redirect()->route('subjects.index')
                        ->with('success', 'Subject updated successfully.');
    }

    public function destroy($id){
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('subjects.index')
                        ->with('success', 'Subject deleted successfully.');
    }
}
