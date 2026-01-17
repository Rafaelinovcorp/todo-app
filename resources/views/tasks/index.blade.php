@extends('layouts.app')

@section('title', 'Minhas Tarefas')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">
            Minhas tarefas
        </h2>

        @if ($tasks->isEmpty())
            <p class="text-gray-500">
                Ainda não tens tarefas criadas.
            </p>
        @else
            <ul class="divide-y">
                @foreach ($tasks as $task)
                    <li class="py-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium">{{ $task->title }}</p>
                            <p class="text-sm text-gray-500">
                                Prioridade: {{ ucfirst($task->priority) }} |
                                Estado: {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
