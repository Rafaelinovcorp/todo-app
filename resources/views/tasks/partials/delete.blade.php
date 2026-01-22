<div
    id="tarefaDeleteModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50"
    onclick="fecharDeletePorOverlay(event)"
>
    <div
        id="tarefaDeleteConteudo"
        class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md"
    >
        <h2 class="text-lg font-semibold text-red-600 mb-4">
            Eliminar tarefa
        </h2>

        <p class="mb-4 text-gray-700">
            Tens a certeza que queres eliminar esta tarefa?
        </p>

        <div class="bg-gray-100 rounded p-3 mb-4 text-sm">
            <p><strong>Título:</strong> <span id="deleteTaskTitulo">—</span></p>
        </div>

        <div class="flex justify-end gap-3">
            <button
                onclick="fecharDelete()"
                class="px-4 py-2 border rounded hover:bg-gray-100"
            >
                Cancelar
            </button>

            <button
                onclick="confirmarDelete()"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
            >
                Eliminar
            </button>
        </div>
    </div>
</div>
