<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Client;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['client', 'assignedTo', 'assignedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhereHas('client', fn($q) => $q->where('name', 'like', "%$search%")
                  ->orWhere('case_number', 'like', "%$search%"));
            });
        }

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('priority')) $query->where('priority', $request->priority);
        if ($request->filled('assigned_to')) $query->where('assigned_to', $request->assigned_to);

        $tasks = $query->orderBy('due_date')->paginate(20);
        $users = User::where('status', 'Active')->get();

        return view('tasks.index', compact('tasks', 'users'));
    }

    public function create()
    {
        $clients = Client::where('status', 'Active')->orderBy('name')->get();
        $users   = User::where('status', 'Active')->get();
        return view('tasks.create', compact('clients', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date'    => 'nullable|date',
            'priority'    => 'required|in:Low,Medium,High,Urgent',
        ]);

        $task = Task::create([
            'client_id'   => $request->client_id,
            'assigned_to' => $request->assigned_to,
            'assigned_by' => auth()->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
            'priority'    => $request->priority,
            'status'      => 'Pending',
        ]);

        UserActivityLog::log('Created task', 'tasks', $task->id);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function edit(Task $task)
    {
        $clients = Client::where('status', 'Active')->orderBy('name')->get();
        $users   = User::where('status', 'Active')->get();
        return view('tasks.edit', compact('task', 'clients', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status'   => 'required|in:Pending,In Progress,Completed',
        ]);

        $task->update([
            'client_id'   => $request->client_id,
            'assigned_to' => $request->assigned_to,
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
            'priority'    => $request->priority,
            'status'      => $request->status,
        ]);

        UserActivityLog::log('Updated task', 'tasks', $task->id);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        UserActivityLog::log('Deleted task', 'tasks', $task->id);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}