<?php

use App\Models\User;
use App\Models\Task;
use App\Models\TaskList;

/*
|--------------------------------------------------------------------------
| INDEX
|--------------------------------------------------------------------------
*/

it('requires authentication to view tasks', function () {
    $this->get(route('tasks.index'))
        ->assertRedirect('/login');
});

it('shows only tasks of the authenticated user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Task::factory()->count(2)->create(['user_id' => $user->id]);
    Task::factory()->count(1)->create(['user_id' => $otherUser->id]);

    $this->actingAs($user)
        ->get(route('tasks.index'))
        ->assertOk()
        ->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 2;
        });
});

/*
|--------------------------------------------------------------------------
| CREATE
|--------------------------------------------------------------------------
*/

it('shows create task page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('tasks.create'))
        ->assertOk()
        ->assertViewIs('tasks.create');
});

/*
|--------------------------------------------------------------------------
| STORE
|--------------------------------------------------------------------------
*/

it('creates a task successfully', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tasks.store'), [
            'title' => 'Nova tarefa',
            'priority' => 'medium',
        ])
        ->assertRedirect(route('tasks.index'));

    $this->assertDatabaseHas('tasks', [
        'title' => 'Nova tarefa',
        'user_id' => $user->id,
        'status' => 'pending',
    ]);
});

it('does not allow creating a task without title', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tasks.store'), [
            'priority' => 'medium',
        ])
        ->assertSessionHasErrors('title');
});

it('attaches task to a list when list_id is provided', function () {
    $user = User::factory()->create();
    $list = TaskList::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post(route('tasks.store'), [
            'title' => 'Tarefa com lista',
            'priority' => 'low',
            'list_id' => $list->id,
        ]);

    $task = Task::first();

    expect($task->lists)->toHaveCount(1);
});

/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

it('updates a task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->putJson(route('tasks.update', $task), [
            'title' => 'Atualizado',
            'priority' => 'high',
            'status' => 'pending',
        ])
        ->assertOk();

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Atualizado',
    ]);
});

it('does not allow editing completed tasks', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'completed',
    ]);

    $this->actingAs($user)
        ->putJson(route('tasks.update', $task), [
            'title' => 'Erro',
            'priority' => 'low',
            'status' => 'completed',
        ])
        ->assertStatus(403);
});

/*
|--------------------------------------------------------------------------
| TOGGLE STATUS
|--------------------------------------------------------------------------
*/

it('toggles task status', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    $this->actingAs($user)
        ->patch(route('tasks.toggle', $task))
        ->assertOk();

    expect($task->fresh()->status)->toBe('completed');
});

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

it('deletes only completed tasks', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'completed',
    ]);

    $this->actingAs($user)
        ->deleteJson(route('tasks.destroy', $task))
        ->assertOk();

    $this->assertDatabaseMissing('tasks', [
        'id' => $task->id,
    ]);
});

it('cannot delete pending tasks', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    $this->actingAs($user)
        ->deleteJson(route('tasks.destroy', $task))
        ->assertStatus(403);
});
