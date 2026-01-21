@extends('layouts.app')

@section('title', 'Minhas Tarefas')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<div class="bg-white rounded-lg shadow p-6">

    {{-- TÍTULO --}}
    <h2 class="text-lg font-semibold mb-4">
        Minhas tarefas
    </h2>

    <div class="flex items-center gap-2">
        <button
            onclick="toggleFiltros()"
            class="px-4 py-2 border rounded hover:bg-gray-100 flex items-center gap-2"
        >
            🔍 Filtros
        </button>

        <a href="{{ route('tasks.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            + Nova tarefa
        </a>
    </div>

    {{-- FILTROS --}}
    <div id="painelFiltros" class="hidden mb-4 p-4 border rounded bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>
                <label class="block text-sm font-medium mb-1">Nome</label>
                <input type="text" id="filtroNome" oninput="aplicarFiltros()"
                       placeholder="Pesquisar tarefa..."
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Prioridade</label>
                <select id="filtroPrioridade" onchange="aplicarFiltros()"
                        class="w-full border rounded px-3 py-2">
                    <option value="">Todas</option>
                    <option value="low">Baixa</option>
                    <option value="medium">Média</option>
                    <option value="high">Alta</option>
                </select>
            </div>

            <div class="mt-3">
                <button
                    onclick="ordenarPorData()"
                    class="px-4 py-2 border rounded hover:bg-gray-100"
                >
                    📅 Ordenar por data mais próxima
                </button>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Estado</label>
                <select id="filtroEstado" onchange="aplicarFiltros()"
                        class="w-full border rounded px-3 py-2">
                    <option value="">Todas</option>
                    <option value="pending">Por fazer</option>
                    <option value="completed">Completas</option>
                </select>
            </div>

            <div class="flex items-end">
                <button onclick="limparFiltros()"
                        class="px-4 py-2 border rounded hover:bg-gray-100 w-full">
                    Limpar filtros
                </button>
            </div>
        </div>
    </div>

    {{-- TAREFAS PENDENTES --}}
    @if ($tasks->isEmpty())
        <p class="text-gray-500">Ainda não tens tarefas pendentes.</p>
    @else
        <ul class="divide-y">
            @foreach ($tasks as $task)
                @php
                    if ($task->status === 'completed') {
                        $color = 'bg-green-100 border-green-400';
                    } elseif ($task->due_date && Carbon::parse($task->due_date)->isPast()) {
                        $color = 'bg-purple-100 border-purple-400';
                    } else {
                        $color = match ($task->priority) {
                            'low' => 'bg-yellow-100 border-yellow-400',
                            'medium' => 'bg-orange-100 border-orange-400',
                            'high' => 'bg-red-100 border-red-400',
                            default => 'bg-gray-100 border-gray-300',
                        };
                    }
                @endphp

                <li
                    class="py-4 flex items-start justify-between task-item border-l-4 rounded {{ $color }}"
                    data-title="{{ strtolower($task->title) }}"
                    data-priority="{{ strtolower($task->priority) }}"
                    data-status="{{ $task->status }}"
                    data-due="{{ $task->due_date ?? '' }}"
                >
                    <div class="flex items-start gap-3">

                        <input type="checkbox"
                               onchange="toggleTask({{ $task->id }}, this)"
                               class="mt-1 h-4 w-4 text-green-600">

                        <div>
                            <p class="font-medium">{{ $task->title }}</p>

                            <p class="text-sm text-gray-600">
                                Prioridade: {{ ucfirst($task->priority) }} |
                                Estado: {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </p>

                            @if($task->due_date)
                                <p class="text-xs text-gray-500">
                                    Termina em {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                </p>
                            @endif

                            <div class="flex gap-3 mt-1">
                                <button onclick="abrirInfo({{ $task->id }})"
                                        class="text-blue-600 hover:underline">
                                    Info
                                </button>

                                <button onclick="abrirEdit({{ $task->id }})"
                                        class="text-green-600 hover:underline">
                                    Editar
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    {{-- TAREFAS COMPLETAS --}}
    @if ($completedTasks->isNotEmpty())
        <div class="mt-8 border-t pt-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">
                Tarefas completas
            </h2>

            <ul class="space-y-3">
                @foreach ($completedTasks as $task)
                    <li
                        class="py-4 task-item border-l-4 rounded bg-green-100 border-green-400"
                        data-title="{{ strtolower($task->title) }}"
                        data-priority="{{ strtolower($task->priority) }}"
                        data-status="{{ $task->status }}"
                    >
                        <div class="flex items-start gap-3">

                            <input type="checkbox" checked
                                   onchange="toggleTask({{ $task->id }}, this)"
                                   class="mt-1 h-4 w-4 text-green-600">

                            <div>
                                <p class="line-through font-medium">
                                    {{ $task->title }}
                                </p>

                                <p class="text-sm text-gray-600">
                                    Concluída em {{ $task->updated_at->format('d/m/Y') }}
                                </p>

                                <button onclick="abrirDelete({{ $task->id }})"
                                        class="text-red-600 hover:underline text-sm mt-1">
                                    Apagar
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

</div>
@endsection

@include('tasks.partials.info')
@include('tasks.partials.edit')
@include('tasks.partials.delete')