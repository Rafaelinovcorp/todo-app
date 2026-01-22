<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\TaskList;

class TaskController extends Controller
{
public function index(Request $request)
{
    $userId = auth()->id();

    $lists = TaskList::where('user_id', $userId)
        ->orderBy('name')
        ->get();

 
    $pendingQuery = Task::where('user_id', $userId)
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc');

  
    $completedQuery = Task::where('user_id', $userId)
        ->where('status', 'completed')
        ->orderBy('updated_at', 'desc');

   
    if ($request->filled('list')) {
        $listId = $request->get('list');

        $pendingQuery->whereHas('lists', function ($q) use ($listId) {
            $q->where('task_lists.id', $listId);
        });

        $completedQuery->whereHas('lists', function ($q) use ($listId) {
            $q->where('task_lists.id', $listId);
        });
    }

    $tasks = $pendingQuery->get();
    $completedTasks = $completedQuery->get();

    return view('tasks.index', compact(
        'tasks',
        'completedTasks',
        'lists'
    ));
}



    public function info($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

public function create()
{
    $lists = TaskList::where('user_id', auth()->id())
        ->orderBy('name')
        ->get();

    return view('tasks.create', compact('lists'));
}



    public function complete(Task $task)
    {
        
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->update([
            'status' => 'completed'
        ]);

        return redirect()->back();
    }


public function update(Request $request, Task $task)
{
  
    if ($task->status === 'completed') {
        return response()->json([
            'message' => 'Tarefas concluídas não podem ser editadas.'
        ], 403);
    }


    if ($task->user_id !== auth()->id()) {
        abort(403);
    }

    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'due_date' => ['nullable', 'date'],
        'start_time' => ['nullable', 'date_format:H:i'],
        'end_time' => ['nullable', 'date_format:H:i'],
        'priority' => ['required', 'in:low,medium,high'],
        'status' => ['required', 'in:pending,in_progress,completed'],
    ]);


    $startTime = $validated['start_time'] ?? null;
    $endTime   = $validated['end_time'] ?? null;
    $dueDate   = $validated['due_date'] ?? null;

  
    if (($startTime || $endTime) && empty($dueDate)) {
        return response()->json([
            'message' => 'A data é obrigatória quando define uma hora.'
        ], 422);
    }

  
    if ($endTime && !$startTime) {
        return response()->json([
            'message' => 'A hora de início é obrigatória quando define uma hora de fim.'
        ], 422);
    }

 
    if ($startTime && $endTime && $endTime <= $startTime) {
        return response()->json([
            'message' => 'A hora de fim deve ser maior que a hora de início.'
        ], 422);
    }

   
    $task->update($validated);

    return response()->json(['success' => true]);
}


public function store(Request $request)
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        'start_time' => ['nullable', 'date_format:H:i'],
        'end_time' => ['nullable', 'date_format:H:i'],
        'priority' => ['required', 'in:low,medium,high'],
        'list_id' => ['nullable', 'exists:task_lists,id'],
    ]);


    $startTime = $validated['start_time'] ?? null;
    $endTime   = $validated['end_time'] ?? null;
    $dueDate   = $validated['due_date'] ?? null;

  
    if (($startTime || $endTime) && empty($dueDate)) {
        return back()->withErrors([
            'due_date' => 'A data é obrigatória quando define uma hora.'
        ])->withInput();
    }

 
    if ($endTime && !$startTime) {
        return back()->withErrors([
            'start_time' => 'A hora de início é obrigatória quando define uma hora de fim.'
        ])->withInput();
    }

 
    if ($startTime && $endTime && $endTime <= $startTime) {
        return back()->withErrors([
            'end_time' => 'A hora de fim deve ser maior que a hora de início.'
        ])->withInput();
    }

    $validated['user_id'] = auth()->id();
    $validated['status'] = 'pending';

    
    $task = Task::create($validated);

  
    if (!empty($validated['list_id'])) {
        $task->lists()->attach($validated['list_id']);
    }

    return redirect()
        ->route('tasks.index', [
            'list' => $validated['list_id'] ?? null
        ])
        ->with('success', 'Tarefa criada com sucesso.');
}



    public function toggleStatus(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->update([
            'status' => $task->status === 'completed'
                ? 'pending'
                : 'completed'
        ]);

        return response()->json([
            'status' => $task->status
        ]);
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        if ($task->status !== 'completed') {
            return response()->json([
                'message' => 'Só é possível apagar tarefas concluídas.'
            ], 403);
        }

        $task->delete();

        return response()->json(['success' => true]);
    }
}
