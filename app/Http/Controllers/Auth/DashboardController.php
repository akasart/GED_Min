<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Routine;
use App\Models\Note;
use App\Models\File;
use App\Models\Reminder;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $tasksCount = Task::count();
        $routinesCount = Routine::whereDate('date', today())->count();
        $notesCount = Note::count();
        $filesCount = File::count();

        $recentTasks = Task::latest()->take(5)->get();
        $todayRoutines = Routine::whereDate('date', today())->get();
        $recentNotes = Note::latest()->take(5)->get();
        $upcomingReminders = Reminder::whereDate('date', '>=', today())->orderBy('date')->take(5)->get()->map(function($r){
            $r->date = Carbon::parse($r->date);
            return $r;
        });

        return view('dashboard', compact(
            'tasksCount', 'routinesCount', 'notesCount', 'filesCount',
            'recentTasks', 'todayRoutines', 'recentNotes', 'upcomingReminders'
        ));
    }
}
