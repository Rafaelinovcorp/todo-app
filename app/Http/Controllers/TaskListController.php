<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
      public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        TaskList::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('tasks.index');
    }

    public function update(Request $request, TaskList $taskList)
    {
        if ($taskList->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $taskList->update([
            'name' => $request->name,
        ]);

        return redirect()->route('tasks.index', [
            'list' => $taskList->id
        ]);
    }

    public function destroy(TaskList $taskList)
    {
        if ($taskList->user_id !== auth()->id()) {
            abort(403);
        }

        // remove relações (pivot)
        $taskList->tasks()->detach();

        $taskList->delete();

        return redirect()->route('tasks.index');
    }
}
