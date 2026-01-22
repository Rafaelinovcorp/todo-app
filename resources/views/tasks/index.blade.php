@extends('layouts.app')

@section('title', 'Minhas Tarefas')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<div class="flex flex-1 w-full">


    {{-- SIDEBAR ESQUERDA --}}
    @include('tasks.partials._sidebar_lists')

    {{-- CONTEÚDO CENTRAL --}}
    <div class="flex-1 overflow-y-auto px-4 py-6 bg-gray-50">

       <div class="bg-white rounded-xl shadow-sm p-6 w-full">



        <button
            onclick="toggleSidebar()"
            class="lg:hidden mb-4 px-3 py-2 border rounded-lg text-sm flex items-center gap-2"
        >
            ☰ Listas
        </button>


            {{-- TÍTULO --}}
            <h2 class="text-xl font-semibold mb-6">
                Minhas tarefas
            </h2>

            {{-- AÇÕES --}}
            <div class="flex flex-wrap items-center gap-2 mb-6">
                <button
                    onclick="toggleFiltros()"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100 flex items-center gap-2"
                >
                    🔍 Filtros
                </button>

                <a href="{{ route('tasks.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm">
                    + Nova tarefa
                </a>
            </div>

            {{-- FILTROS --}}
            <div id="painelFiltros" class="hidden mb-6 p-4 border rounded-lg bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="block text-sm font-medium mb-1">Nome</label>
                        <input type="text" id="filtroNome" oninput="aplicarFiltros()"
                               placeholder="Pesquisar tarefa..."
                               class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Prioridade</label>
                        <select id="filtroPrioridade" onchange="aplicarFiltros()"
                                class="w-full border rounded-lg px-3 py-2">
                            <option value="">Todas</option>
                            <option value="low">Baixa</option>
                            <option value="medium">Média</option>
                            <option value="high">Alta</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <button
                            onclick="ordenarPorData()"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-100 w-full">
                            📅 Ordenar por data mais próxima
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Estado</label>
                        <select id="filtroEstado" onchange="aplicarFiltros()"
                                class="w-full border rounded-lg px-3 py-2">
                            <option value="">Todas</option>
                            <option value="pending">Por fazer</option>
                            <option value="completed">Completas</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button onclick="limparFiltros()"
                                class="px-4 py-2 border rounded-lg hover:bg-gray-100 w-full">
                            Limpar filtros
                        </button>
                    </div>
                </div>
            </div>

            {{-- TAREFAS PENDENTES --}}
            @if ($tasks->isEmpty())
                <p class="text-gray-500">Ainda não tens tarefas pendentes.</p>
            @else
                <ul class="space-y-3">
                    @foreach ($tasks as $task)
                        @php
                            if ($task->due_date && Carbon::parse($task->due_date)->isPast()) {
                                $color = 'bg-purple-50 border-purple-400';
                            } else {
                                $color = match ($task->priority) {
                                    'low' => 'bg-yellow-50 border-yellow-400',
                                    'medium' => 'bg-orange-50 border-orange-400',
                                    'high' => 'bg-red-50 border-red-400',
                                    default => 'bg-gray-50 border-gray-300',
                                };
                            }
                        @endphp

                        <li
                            class="task-item flex gap-4 p-4 rounded-lg border-l-4 {{ $color }} hover:shadow-sm transition"
                            data-title="{{ strtolower($task->title) }}"
                            data-priority="{{ strtolower($task->priority) }}"
                            data-status="{{ $task->status }}"
                            data-due="{{ $task->due_date ?? '' }}"
                        >
                            <input type="checkbox"
                                   onchange="toggleTask({{ $task->id }}, this)"
                                   class="mt-1 h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-0">

                            <div class="flex-1">
                                <p class="font-medium">{{ $task->title }}</p>

                                <p class="text-sm text-gray-600">
                                    Prioridade: {{ ucfirst($task->priority) }}
                                </p>

                                @if($task->due_date)
                                    <p class="text-xs text-gray-500">
                                        Termina em {{ Carbon::parse($task->due_date)->format('d/m/Y') }}
                                    </p>
                                @endif

                                <div class="flex gap-4 mt-2 text-sm">
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
                        </li>
                    @endforeach
                </ul>
            @endif

            {{-- TAREFAS COMPLETAS --}}
            @if ($completedTasks->isNotEmpty())
                <div class="mt-10 pt-6 border-t">
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">
                        Tarefas completas
                    </h2>

                    <ul class="space-y-3">
                        @foreach ($completedTasks as $task)
                            <li
                                class="task-item flex gap-4 p-4 rounded-lg border-l-4 bg-green-50 border-green-400"
                                data-title="{{ strtolower($task->title) }}"
                                data-priority="{{ strtolower($task->priority) }}"
                                data-status="{{ $task->status }}"
                            >
                                <input type="checkbox" checked
                                       onchange="toggleTask({{ $task->id }}, this)"
                                       class="mt-1 h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-0">

                                <div class="flex-1">
                                    <p class="line-through font-medium">
                                        {{ $task->title }}
                                    </p>

                                    <p class="text-sm text-gray-600">
                                        Concluída em {{ $task->updated_at->format('d/m/Y') }}
                                    </p>

                                    <button onclick="abrirDelete({{ $task->id }})"
                                            class="text-red-600 hover:underline text-sm mt-2">
                                        Apagar
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>
    </div>

    <div
    id="sidebarOverlay"
    onclick="toggleSidebar()"
    class="fixed inset-0 bg-black/40 hidden z-30 lg:hidden">
</div>

</div>
@endsection

@include('tasks.partials.info')
@include('tasks.partials.edit')
@include('tasks.partials.delete')
@include('tasks.partials._create_list_modal')
@include('tasks.partials._edit_list_modal')
@include('tasks.partials._delete_list_modal')

