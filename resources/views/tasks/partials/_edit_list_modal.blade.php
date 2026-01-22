<div id="modalEditarLista"
     onclick="fecharEditarListaPorOverlay(event)"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div id="modalEditarListaConteudo"
         class="bg-white rounded-lg shadow w-full max-w-sm p-6">

        <h3 class="text-lg font-semibold mb-4">
            Editar lista
        </h3>

        <form id="formEditarLista" method="POST">
            @csrf
            @method('PUT')

            <input type="text"
                   id="editListName"
                   name="name"
                   required
                   class="w-full border rounded px-3 py-2 mb-4">

            <div class="flex justify-end gap-2">
                <button type="button"
                        onclick="fecharEditarLista()"
                        class="px-4 py-2 border rounded">
                    Cancelar
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
