<?php

use App\Models\User;
use App\Models\TaskList;

/*
|--------------------------------------------------------------------------
| STORE
|--------------------------------------------------------------------------
*/

it('creates a task list', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('task-lists.store'), [
            'name' => 'Trabalho',
        ])
        ->assertRedirect(route('tasks.index'));

    $this->assertDatabaseHas('task_lists', [
        'name' => 'Trabalho',
        'user_id' => $user->id,
    ]);
});

/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

it('updates a task list', function () {
    $user = User::factory()->create();
    $list = TaskList::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->put(route('task-lists.update', $list), [
            'name' => 'Nova Lista',
        ])
        ->assertRedirect(route('tasks.index', ['list' => $list->id]));

    $this->assertDatabaseHas('task_lists', [
        'id' => $list->id,
        'name' => 'Nova Lista',
    ]);
});

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

it('deletes a task list', function () {
    $user = User::factory()->create();
    $list = TaskList::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->delete(route('task-lists.destroy', $list))
        ->assertRedirect(route('tasks.index'));

    $this->assertDatabaseMissing('task_lists', [
        'id' => $list->id,
    ]);
});
