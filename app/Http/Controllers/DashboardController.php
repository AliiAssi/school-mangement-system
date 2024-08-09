<?php

namespace App\Http\Controllers;

use App\Models\TimeTable;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class DashboardController extends Controller
{
    public function index()
    {
        // Check if the user is not an admin
        if (Auth::user()->isAdmin === 0) {
            // Get the currently authenticated user
            $user = Auth::user();

            // Fetch timetables for the specific user
            $timetables = TimeTable::where('user_id', $user->id)->get();

            // Return a view with the timetables
            return view('dashboard', compact('timetables'));
        }

        // Return the default dashboard view for admins
        return view('dashboard');
    }
}
