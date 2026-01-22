<div id="modalApagarLista"
     onclick="fecharApagarListaPorOverlay(event)"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div id="modalApagarListaConteudo"
         class="bg-white rounded-lg shadow w-full max-w-sm p-6">
        <h3 class="text-lg font-semibold mb-4 text-red-600">
            Apagar lista
        </h3>

        <p class="text-sm text-gray-600 mb-4">
            Esta ação remove apenas a lista.  
            As tarefas não serão apagadas.
        </p>

        <form id="formApagarLista" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end gap-2">
                <button type="button"
                        onclick="fecharApagarLista()"
                        class="px-4 py-2 border rounded">
                    Cancelar
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded">
                    Apagar
                </button>
            </div>
        </form>
    </div>
</div>
