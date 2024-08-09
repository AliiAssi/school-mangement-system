<?php

namespace App\Http\Controllers;

use App\Models\_Class;
use App\Models\TimeTable;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    // Display a listing of the classes
    public function index()
    {
        $classes = _Class::paginate(4); // Paginate the list of classes
        return view('classes.index', compact('classes'));
    }

    // Show the form for creating a new class
    public function create()
    {
        return view('classes.create');
    }

    // Store a newly created class in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string', // grade is required
        ]);

        _Class::create([
            'name' => $request->input('name'),
            'grade' => $request->input('grade'), // Store grade
        ]);

        return redirect()->route('classes.index')
                         ->with('success', 'Class created successfully.');
    }

    // Display the specified class
    public function show($id)
    {
        $classId = $id;
        $classes = _Class::all();
        $timetables = TimeTable::where('class_id', $classId)->get();
        return view('timetables.index', compact('classes', 'timetables', 'classId'));
    }

    // Show the form for editing the specified class
    public function edit($id)
    {
        $class = _Class::findOrFail($id);
        return view('classes.edit', compact('class'));
    }

    // Update the specified class in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string', // grade is required
        ]);

        $class = _Class::findOrFail($id);
        $class->update([
            'name' => $request->input('name'),
            'grade' => $request->input('grade'), // Update grade
        ]);

        return redirect()->route('classes.index')
                         ->with('success', 'Class updated successfully.');
    }

    // Remove the specified class from storage
    public function destroy($id)
    {
        $class = _Class::findOrFail($id);
        $class->delete();

        return redirect()->route('classes.index')
                         ->with('success', 'Class deleted successfully.');
    }
}
