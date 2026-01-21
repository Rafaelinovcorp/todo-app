{{-- Modal Editar Tarefa --}}
<div
    id="tarefaEditModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50"
    onclick="fecharEditPorOverlay(event)"
>
    <div
        id="tarefaEditConteudo"
        class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative"
    >
        <button
            onclick="fecharEdit()"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600"
            type="button"
        >
            ✕
        </button>

        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            Editar tarefa
        </h2>

        <form id="editForm">
            <input type="hidden" id="editTaskId">

            {{-- Título --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Título</label>
                <input
                    type="text"
                    id="editTitle"
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            {{-- Prioridade --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">
                    Prioridade
                </label>
                <select
                    id="editPriority"
                    class="w-full border rounded px-3 py-2"
                >
                    <option value="low">Baixa</option>
                    <option value="medium">Média</option>
                    <option value="high">Alta</option>
                </select>
            </div>

            {{-- Descrição --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Descrição</label>
                <textarea
                    id="editDescription"
                    class="w-full border rounded px-3 py-2"
                ></textarea>
            </div>

            {{-- Datas / Horas --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-sm text-gray-600">Data limite</label>
                   <input
                        type="date"
                        id="editDueDate"
                        class="w-full border rounded px-3 py-2"
                        min="{{ now()->toDateString() }}"
                    >

                </div>

                <div>
                    <label class="text-sm text-gray-600">Hora início</label>
                    <input type="time" id="editStartTime" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="text-sm text-gray-600">Hora fim</label>
                    <input type="time" id="editEndTime" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            {{-- Botão --}}
            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    onclick="fecharEdit()"
                    class="px-4 py-2 border rounded"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                >
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
