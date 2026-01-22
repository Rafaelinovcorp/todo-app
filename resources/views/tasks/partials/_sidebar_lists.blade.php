<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-50 border-r
           transform -translate-x-full transition-transform
           lg:static lg:translate-x-0 lg:flex lg:flex-col"
>



    {{-- Header --}}
    <div class="p-4 border-b">
        <h2 class="text-sm font-semibold text-gray-700">
            Listas
        </h2>
    </div>

    {{-- Listas (scroll) --}}
    <div class="flex-1 overflow-y-auto p-2 space-y-1">
            {{-- Todas as tarefas --}}
            <a href="{{ route('tasks.index') }}"
               class="block px-3 py-2 rounded text-sm
               {{ request()->missing('list') ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                Todas as tarefas
            </a>
    
            {{-- Listas do utilizador --}}
           @foreach ($lists as $list)
        <div class="group flex items-center justify-between px-3 py-2 rounded-md
            {{ request('list') == $list->id ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100' }}">
    
            <a href="{{ route('tasks.index', ['list' => $list->id]) }}"
               class="flex-1 text-sm font-medium truncate">
                {{ $list->name }}
            </a>
    
            <div class="hidden group-hover:flex items-center gap-1">
                <button
                    onclick="abrirEditarLista({{ $list->id }}, '{{ addslashes($list->name) }}')"
                    class="text-gray-500 hover:text-blue-600 text-xs">
                    ✏️
                </button>
    
                <button
                    onclick="abrirApagarLista({{ $list->id }})"
                    class="text-gray-500 hover:text-red-600 text-xs">
                    🗑️
                </button>
            </div>
        </div>
    @endforeach

    </div>

    {{-- Footer --}}
    <div class="p-4 border-t">
        <button
    onclick="abrirModalLista()"
    class="w-full text-sm text-blue-600 hover:underline">
    + Nova lista
</button>
    </div>
</aside>
