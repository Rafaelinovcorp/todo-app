@extends('layouts.app')

@section('title', 'Nova tarefa')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-xl mx-auto">
        <h2 class="text-lg font-semibold mb-4">
            Criar nova tarefa
        </h2>

        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 font-medium">Título</label>
                <input type="text" name="title"
                       value="{{ old('title') }}"
                       class="w-full border rounded px-3 py-2"
                       required>
                @error('title')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Descrição</label>
                <textarea name="description"
                          class="w-full border rounded px-3 py-2"
                          rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Data limite</label>
                <input 
                    type="date"
                    name="due_date"
                    min="{{ now()->toDateString() }}"
                    value="{{ old('due_date') }}"
                    class="w-full border rounded px-3 py-2">

            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Hora da tarefa</label>

                <div class="flex gap-4">
                    <label class="flex items-center gap-1">
                        <input type="radio" name="time_type" value="none" checked>
                        Sem hora
                    </label>

                    <label class="flex items-center gap-1">
                        <input type="radio" name="time_type" value="single">
                        Hora específica
                    </label>

                    <label class="flex items-center gap-1">
                        <input type="radio" name="time_type" value="range">
                        Intervalo
                    </label>
                </div>
            </div>

            <div id="single-time" class="mb-4 hidden">
                <label class="block mb-1 font-medium">Hora</label>
                <input type="time" name="start_time"
                       value="{{ old('start_time') }}"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div id="range-time" class="mb-4 hidden">
                <label class="block mb-1 font-medium">Hora de início</label>
                <input type="time" name="start_time"
                       value="{{ old('start_time') }}"
                       class="w-full border rounded px-3 py-2 mb-2">

                <label class="block mb-1 font-medium">Hora de fim</label>
                <input type="time" name="end_time"
                       value="{{ old('end_time') }}"
                       class="w-full border rounded px-3 py-2">
            </div>

            @error('start_time')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
            
            @error('end_time')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
            
            @error('due_date')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror



            <div class="mb-6">
                <label class="block mb-1 font-medium">Prioridade</label>
                <select name="priority"
                        class="w-full border rounded px-3 py-2">
                    <option value="medium">Média</option>
                    <option value="low">Baixa</option>
                    <option value="high">Alta</option>
                </select>
                
            </div>
                        <div class="mb-6">
                <label class="block mb-1 font-medium">
                    Lista
                </label>

                <select name="list_id"
                        class="w-full border rounded px-3 py-2">
                    <option value="">Sem lista</option>

                    @foreach ($lists as $list)
                        <option value="{{ $list->id }}"
                            {{ old('list_id') == $list->id ? 'selected' : '' }}>
                            {{ $list->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="flex justify-end gap-2">
                <a href="{{ route('tasks.index') }}"
                   class="px-4 py-2 bg-gray-200 rounded">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>

   

@endsection
