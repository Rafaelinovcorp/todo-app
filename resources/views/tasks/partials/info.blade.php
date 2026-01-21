{{-- Modal Informação da Tarefa --}}
<div 
    id="tarefaInfoModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50"
    onclick="fecharInfoPorOverlay(event)"
>
    <div 
        id="tarefaInfoConteudo"
        class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative"
    >

        {{-- Fechar --}}
        <button 
            onclick="fecharInfo()"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600"
            type="button"
        >
            ✕
        </button>

        {{-- Título --}}
        <h2 id="infoTitulo" class="text-xl font-semibold text-gray-800 mb-4">
            --
        </h2>

        {{-- Descrição --}}
        <div class="mb-4">
            <p class="text-sm text-gray-500 mb-1">Descrição</p>
            <p id="infoDescricao" class="text-gray-700">
                --
            </p>
        </div>

        {{-- Estado --}}
        <div class="mb-3 flex items-center gap-2">
            <span class="text-sm text-gray-500">Estado:</span>
            <span 
                id="infoEstado" 
                class="px-3 py-1 rounded-full text-sm font-medium"
            >
                --
            </span>
        </div>

        {{-- Prioridade --}}
        <div class="mb-3">
            <span class="text-sm text-gray-500">Prioridade:</span>
            <span 
                id="infoPrioridade" 
                class="ml-2 font-medium text-gray-700"
            >
                --
            </span>
        </div>

        {{-- Horário --}}
        <div class="mb-3">
            <span class="text-sm text-gray-500">Horário:</span>
            <span id="infoHorario" class="ml-2 text-gray-700 font-medium">
                --
            </span>
        </div>


        {{-- Datas --}}
        <div class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-600">
            <div>
                <p class="text-gray-500">Criada em</p>
                <p id="infoCriadaEm">--</p>
            </div>

            <div>
                <p class="text-gray-500">Data limite</p>
                <p id="infoDataLimite">--</p>
            </div>
        </div>

    </div>
</div>
