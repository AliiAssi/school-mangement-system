<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->isAdmin == 0)  return redirect()->back();
        // Define the file path relative to the storage directory
        $filePath = 'util/settings.txt';

        // Initialize an array to hold the settings
        $settings = [
            'start_time' => 'Not Set',
            'end_time' => 'Not Set',
            'session_duration' => 'Not Set',
            'delay_between_sessions' => 'Not Set',
            'break_start' => 'Not Set',
            'break_end' => 'Not Set',
            'off_days' => []
        ];

        // Check if the file exists
        if (Storage::disk('local')->exists($filePath)) {
            // Read the entire file content
            $fileContent = Storage::disk('local')->get($filePath);

            // Split the content into lines
            $lines = explode("\n", $fileContent);

            // Process each line
            foreach ($lines as $line) {
                // Skip empty lines
                if (empty(trim($line))) {
                    continue;
                }

                // Split the line into key and value
                list($key, $value) = explode(': ', $line, 2) + [null, null];
                if ($key && $value) {
                    if ($key === 'off_days') {
                        $settings[$key] = explode(', ', $value);
                    } else {
                        $settings[$key] = $value;
                    }
                }
            }
        }

        // Pass the settings data to the view
        return view('settings.index', ['settings' => $settings]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('settings.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Define validation rules
        $rules = [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'session_duration' => 'required|integer|min:1',
            'delay_between_sessions' => 'required|integer|min:0',
            'break_start' => 'required|date_format:H:i',
            'break_end' => 'required|date_format:H:i',
            'off_days' => 'required|array|min:1',
            'off_days.*' => 'in:sunday,saturday,monday,tuesday,wednesday,thursday,friday'
        ];

        // Validate the request data
        $validatedData = $request->validate($rules);

        // Remove the _token and _method fields
        unset($validatedData['_token']);
        unset($validatedData['_method']);

        // Prepare the data for saving
        $formattedData = "";
        foreach ($validatedData as $key => $value) {
            if (is_array($value)) {
                $formattedData .= $key . ": " . implode(', ', $value) . "\n";
            } else {
                $formattedData .= $key . ": " . $value . "\n";
            }
        }

        // Define the file path relative to the storage directory
        $filePath = 'util/settings.txt';

        // Save the formatted data to the file
        Storage::disk('local')->put($filePath, $formattedData);

        // Redirect with success message
        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
