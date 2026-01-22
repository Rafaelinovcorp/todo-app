/* =========================
   CSRF TOKEN
   ========================= */
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute('content');


/* =========================
   INFO MODAL
   ========================= */
async function abrirInfo(id) {
    try {
        const response = await fetch(`/tasks/${id}/info`);
        if (!response.ok) throw new Error();

        const tarefa = await response.json();

        document.getElementById('infoTitulo').innerText = tarefa.title;
        document.getElementById('infoDescricao').innerText = tarefa.description ?? 'Sem descrição';
        document.getElementById('infoPrioridade').innerText = tarefa.priority ?? 'Normal';

        document.getElementById('infoCriadaEm').innerText =
            new Date(tarefa.created_at).toLocaleDateString('pt-PT');

        document.getElementById('infoDataLimite').innerText =
            tarefa.due_date
                ? new Date(tarefa.due_date).toLocaleDateString('pt-PT')
                : 'Sem data limite';

        const estadoEl = document.getElementById('infoEstado');
        estadoEl.innerText = tarefa.status.replace('_', ' ');
        estadoEl.className = 'px-3 py-1 rounded-full text-sm font-medium';

        switch (tarefa.status) {
            case 'pending':
                estadoEl.classList.add('bg-yellow-100', 'text-yellow-700');
                break;
            case 'in_progress':
                estadoEl.classList.add('bg-blue-100', 'text-blue-700');
                break;
            case 'completed':
                estadoEl.classList.add('bg-green-100', 'text-green-700');
                break;
        }

        const horarioEl = document.getElementById('infoHorario');
        const startTime = tarefa.start_time;
        const endTime = tarefa.end_time;

        if (!startTime && !endTime) {
            horarioEl.innerText = 'Sem horário definido';
        } else if (startTime && !endTime) {
            horarioEl.innerText = startTime.substring(0, 5);
        } else {
            horarioEl.innerText = `${startTime.substring(0, 5)} → ${endTime.substring(0, 5)}`;
        }

        document.getElementById('tarefaInfoModal').classList.remove('hidden');

    } catch {
        alert('Não foi possível carregar a tarefa.');
    }
}

function fecharInfo() {
    document.getElementById('tarefaInfoModal')?.classList.add('hidden');
}

function fecharInfoPorOverlay(event) {
    const modal = document.getElementById('tarefaInfoConteudo');
    if (modal && !modal.contains(event.target)) {
        fecharInfo();
    }
}


/* =========================
   EDIT MODAL
   ========================= */
async function abrirEdit(id) {
    try {
        const response = await fetch(`/tasks/${id}/info`);
        if (!response.ok) throw new Error();

        const tarefa = await response.json();

        if (tarefa.status === 'completed') {
            alert('Tarefas concluídas não podem ser editadas.');
            return;
        }

        document.getElementById('editTaskId').value = tarefa.id;
        document.getElementById('editTitle').value = tarefa.title;
        document.getElementById('editDescription').value = tarefa.description ?? '';
        document.getElementById('editDueDate').value = tarefa.due_date ?? '';
        document.getElementById('editStartTime').value = tarefa.start_time ?? '';
        document.getElementById('editEndTime').value = tarefa.end_time ?? '';
        document.getElementById('editPriority').value = tarefa.priority;

        document.getElementById('tarefaEditModal').classList.remove('hidden');

    } catch {
        alert('Não foi possível carregar a tarefa.');
    }
}


/* =========================
   SUBMIT EDIT FORM
   ========================= */
document.getElementById('editForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();

    const dueDate = document.getElementById('editDueDate').value;

    if (dueDate) {
        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0);

        const dataSelecionada = new Date(dueDate);
        if (dataSelecionada < hoje) {
            alert('Não podes definir uma data de término no passado.');
            return;
        }
    }

    const id = document.getElementById('editTaskId').value;

    const data = {
        title: document.getElementById('editTitle').value,
        description: document.getElementById('editDescription').value,
        due_date: dueDate || null,
        start_time: document.getElementById('editStartTime').value || null,
        end_time: document.getElementById('editEndTime').value || null,
        priority: document.getElementById('editPriority').value,
        status: 'pending'
    };

    try {
        const response = await fetch(`/tasks/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            const err = await response.json();
            alert(err.message ?? 'Erro ao guardar tarefa');
            return;
        }

        fecharEdit();
        location.reload();

    } catch {
        alert('Erro ao guardar tarefa.');
    }
});

function fecharEdit() {
    document.getElementById('tarefaEditModal')?.classList.add('hidden');
}

function fecharEditPorOverlay(event) {
    const modal = document.getElementById('tarefaEditConteudo');
    if (modal && !modal.contains(event.target)) {
        fecharEdit();
    }
}


/* =========================
   TOGGLE STATUS
   ========================= */
async function toggleTask(id, checkbox) {
    try {
        const response = await fetch(`/tasks/${id}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error();
        location.reload();

    } catch {
        alert('Erro ao atualizar a tarefa.');
        checkbox.checked = !checkbox.checked;
    }
}


/* =========================
   DELETE
   ========================= */

let taskParaEliminar = null;

async function confirmarDelete() {
    if (!taskParaEliminar) return;

    try {
        const response = await fetch(`/tasks/${taskParaEliminar}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error();
        location.reload();

    } catch {
        alert('Erro ao eliminar tarefa.');
    }
}

/* =========================
   DELETE MODAL
   ========================= */

async function abrirDelete(id) {
    taskParaEliminar = id;

    try {
        const response = await fetch(`/tasks/${id}/info`);
        if (!response.ok) throw new Error();

        const tarefa = await response.json();

        // Preencher dados no modal
        const tituloEl = document.getElementById('deleteTaskTitulo');
        if (tituloEl) {
            tituloEl.innerText = tarefa.title;
        }

        // Abrir modal
        const modal = document.getElementById('tarefaDeleteModal');
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

    } catch {
        alert('Não foi possível carregar a tarefa.');
    }
}


function fecharDelete() {
    const modal = document.getElementById('tarefaDeleteModal');
    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    taskParaEliminar = null;
}

function fecharDeletePorOverlay(event) {
    const conteudo = document.getElementById('tarefaDeleteConteudo');
    if (conteudo && !conteudo.contains(event.target)) {
        fecharDelete();
    }
}





/* =========================
   FILTROS
   ========================= */
function toggleFiltros() {
    document.getElementById('painelFiltros')?.classList.toggle('hidden');
}

function aplicarFiltros() {
    const nome = document.getElementById('filtroNome')?.value.toLowerCase() ?? '';
    const prioridade = document.getElementById('filtroPrioridade')?.value ?? '';
    const estado = document.getElementById('filtroEstado')?.value ?? '';

    document.querySelectorAll('.task-item').forEach(task => {
        let visivel = true;

        if (nome && !task.dataset.title.includes(nome)) visivel = false;
        if (prioridade && task.dataset.priority !== prioridade) visivel = false;
        if (estado && task.dataset.status !== estado) visivel = false;

        task.style.display = visivel ? '' : 'none';
    });
}

function limparFiltros() {
    document.getElementById('filtroNome').value = '';
    document.getElementById('filtroPrioridade').value = '';
    document.getElementById('filtroEstado').value = '';
    aplicarFiltros();
}

function ordenarPorData() {
    const lista = document.getElementById('listaTarefas');
    if (!lista) return;

    const tarefas = Array.from(lista.querySelectorAll('.task-item'));

    tarefas.sort((a, b) => {
        const dataA = a.dataset.due;
        const dataB = b.dataset.due;

        if (!dataA && !dataB) return 0;
        if (!dataA) return 1;
        if (!dataB) return -1;

        return new Date(dataA) - new Date(dataB);
    });

    tarefas.forEach(task => lista.appendChild(task));
}



function abrirModalLista() {
    document.getElementById('modalNovaLista')?.classList.remove('hidden');
    document.getElementById('modalNovaLista')?.classList.add('flex');
}

function fecharModalLista() {
    document.getElementById('modalNovaLista')?.classList.add('hidden');
    document.getElementById('modalNovaLista')?.classList.remove('flex');
}

function abrirEditarLista(id, nome) {
    document.getElementById('editListName').value = nome;
    document.getElementById('formEditarLista').action = `/task-lists/${id}`;
    document.getElementById('modalEditarLista').classList.remove('hidden');
    document.getElementById('modalEditarLista').classList.add('flex');
}

function fecharEditarLista() {
    document.getElementById('modalEditarLista').classList.add('hidden');
}

function abrirApagarLista(id) {
    document.getElementById('formApagarLista').action = `/task-lists/${id}`;
    document.getElementById('modalApagarLista').classList.remove('hidden');
    document.getElementById('modalApagarLista').classList.add('flex');
}

function fecharApagarLista() {
    document.getElementById('modalApagarLista').classList.add('hidden');
}

function fecharApagarListaPorOverlay(event) {
    const modal = document.getElementById('modalApagarListaConteudo');
    if (modal && !modal.contains(event.target)) {
        fecharApagarLista();
    }
}

function fecharEditarListaPorOverlay(event) {
    const modal = document.getElementById('modalEditarListaConteudo');
    if (modal && !modal.contains(event.target)) {
        fecharEditarLista();
    }
}

function fecharNovaListaPorOverlay(event) {
    const modal = document.getElementById('modalNovaListaConteudo');
    if (modal && !modal.contains(event.target)) {
        fecharModalLista();
    }
}
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}


window.fecharApagarListaPorOverlay = fecharApagarListaPorOverlay;
window.fecharEditarListaPorOverlay = fecharEditarListaPorOverlay;
window.fecharNovaListaPorOverlay = fecharNovaListaPorOverlay;

window.fecharEditarLista = fecharEditarLista;
window.fecharApagarLista = fecharApagarLista;


window.abrirEditarLista = abrirEditarLista;
window.abrirApagarLista = abrirApagarLista;


window.abrirModalLista = abrirModalLista;
window.fecharModalLista = fecharModalLista;


window.abrirInfo = abrirInfo;
window.fecharInfo = fecharInfo;
window.fecharInfoPorOverlay = fecharInfoPorOverlay;

window.abrirEdit = abrirEdit;
window.fecharEdit = fecharEdit;
window.fecharEditPorOverlay = fecharEditPorOverlay;

window.toggleTask = toggleTask;

window.toggleFiltros = toggleFiltros;
window.aplicarFiltros = aplicarFiltros;
window.limparFiltros = limparFiltros;

window.ordenarPorData = ordenarPorData;
window.confirmarDelete = confirmarDelete;

window.abrirDelete = abrirDelete;
window.fecharDelete = fecharDelete;
window.fecharDeletePorOverlay = fecharDeletePorOverlay;
window.confirmarDelete = confirmarDelete;

