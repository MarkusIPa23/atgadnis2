<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())
            ->where('completed', false) // Rādīt tikai nepabeigtos uzdevumus
            ->latest()
            ->get();

        $totalTasks = $tasks->count();
        $completedTasks = Task::where('user_id', auth()->id())
            ->where('completed', true)
            ->count();
        $pendingTasks = $totalTasks;

        return view('tasks.index', compact('tasks', 'totalTasks', 'completedTasks', 'pendingTasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'due_date' => 'nullable|date', // Pievienoju validāciju termiņam
        ]);

        Task::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'due_date' => $request->due_date, // Saglabāju termiņu
        ]);

        return back()->with('success', 'Uzdevums pievienots!');
    }

    public function toggle(Task $task)
    {
        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        return back();
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back();
    }

    public function favorite(Task $task)
    {
        $task->update([
            'is_favorite' => !$task->is_favorite, // Pārslēdz favorīta statusu
        ]);

        return back();
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $task->update([
            'title' => $request->title,
            'due_date' => $request->due_date,
        ]);

        return back()->with('success', 'Uzdevums atjaunināts!');
    }
}
