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

    // 🔹 Listas do utilizador (sidebar)
    $lists = TaskList::where('user_id', $userId)
        ->orderBy('name')
        ->get();

    // 🔹 Query base das tarefas pendentes
    $pendingQuery = Task::where('user_id', $userId)
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc');

    // 🔹 Query base das tarefas concluídas
    $completedQuery = Task::where('user_id', $userId)
        ->where('status', 'completed')
        ->orderBy('updated_at', 'desc');

    // 🔹 Filtro por lista (aplica às duas)
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

        
        if (($validated['start_time'] ?? null || $validated['end_time'] ?? null)
            && empty($validated['due_date'])) {
            return response()->json([
                'message' => 'A data é obrigatória quando define uma hora.'
            ], 422);
        }

        if (!empty($validated['end_time']) && empty($validated['start_time'])) {
            return response()->json([
                'message' => 'A hora de início é obrigatória quando define uma hora de fim.'
            ], 422);
        }

        if (!empty($validated['start_time']) && !empty($validated['end_time'])) {
            if ($validated['end_time'] <= $validated['start_time']) {
                return response()->json([
                    'message' => 'A hora de fim deve ser maior que a hora de início.'
                ], 422);
            }
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

    // validações de hora (mantidas)
    if (($validated['start_time'] ?? null || $validated['end_time'] ?? null)
        && empty($validated['due_date'])) {
        return back()->withErrors([
            'due_date' => 'A data é obrigatória quando define uma hora.'
        ])->withInput();
    }

    if (!empty($validated['end_time']) && empty($validated['start_time'])) {
        return back()->withErrors([
            'start_time' => 'A hora de início é obrigatória quando define uma hora de fim.'
        ])->withInput();
    }

    if (!empty($validated['start_time']) && !empty($validated['end_time'])) {
        if ($validated['end_time'] <= $validated['start_time']) {
            return back()->withErrors([
                'end_time' => 'A hora de fim deve ser maior que a hora de início.'
            ])->withInput();
        }
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
