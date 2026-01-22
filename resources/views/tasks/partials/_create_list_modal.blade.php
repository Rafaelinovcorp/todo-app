<div id="modalNovaLista"
     onclick="fecharNovaListaPorOverlay(event)"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div id="modalNovaListaConteudo"
         class="bg-white rounded-lg shadow w-full max-w-sm p-6">

        <h3 class="text-lg font-semibold mb-4">
            Nova lista
        </h3>

        <form method="POST" action="{{ route('task-lists.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Nome da lista
                </label>
                <input type="text"
                       name="name"
                       required
                       autofocus
                       class="w-full border rounded px-3 py-2"
                       placeholder="Ex: Faculdade">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button"
                        onclick="fecharModalLista()"
                        class="px-4 py-2 border rounded hover:bg-gray-100">
                    Cancelar
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Criar
                </button>
            </div>
        </form>
    </div>
</div>
