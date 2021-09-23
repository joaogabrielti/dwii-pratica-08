@extends('layouts.app', ['page' => 'veterinario', 'title' => 'Cadastro de Veterinários', 'icon' => 'file-earmark-person'])

@section('content')
<form class="row gx-2 mb-2" action="{{ route('veterinario.index') }}" method="POST" autocomplete="off">
    @csrf
    <div class="col-12 col-md-3">
        <button class="btn btn-primary w-100" type="button" onclick="create()">Cadastrar Novo Veterinário</button>
    </div>
    <div class="col-11 col-md-8">
        <input class="form-control" type="text" name="search" id="search" placeholder="Buscar" required>
    </div>
    <div class="col-1">
        <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search"></i></button>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped" id="datatable">
        <thead class="table-secondary text-center text-uppercase">
            <th>ID</th>
            <th>NOME</th>
            <th>CRMV</th>
            <th>ESPECIALIDADE</th>
            <th>AÇÕES</th>
        </thead>
        <tbody></tbody>
    </table>
</div>
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                <div class="mb-1">
                    <label class="form-label mb-0" for="nome">Nome Completo</label>
                    <input class="form-control" type="text" name="nome" id="nome">
                </div>
                <div class="mb-1">
                    <label class="form-label mb-0" for="crmv">CRMV</label>
                    <input class="form-control" type="text" name="crmv" id="crmv">
                </div>
                <div class="mb-1">
                    <label class="form-label mb-0" for="especialidade_id">Especialidade</label>
                    <select class="form-select" name="especialidade_id" id="especialidade_id" required>
                        <option hidden disabled selected value>Selecione uma especialidade...</option>
                        @foreach (\App\Models\Especialidade::all() as $esp)
                        <option value="{{ $esp->id }}">{{ $esp->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="save(event)">Salvar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="removeModal" tabindex="-1" aria-labelledby="removeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeModalLabel">Remover Veterinário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não, cancelar!</button>
                <button type="button" class="btn btn-danger" onclick="del()">Sim, remover!</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showModalLabel">Informações do Veterinário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="id_remove">
@endsection


@section('scripts')
<script type="text/javascript">
    const veterinarios = JSON.parse('{!! json_encode($veterinarios) !!}');
    for (const c of veterinarios) {
        row = getTableRow(c);
        $('#datatable>tbody').append(row);
    }

    $.ajaxSetup({
        'X-CSRF-TOKEN': "{{ csrf_token() }}",
    });

    function create() {
        $('#createModal').modal().find('.modal-title').text("Novo Veterinário");

        $("#id").val('');
        $("#nome").val('');
        $("#crmv").val('');
        $("#especialidade_id").val('');

        $('#createModal').modal('show');
    }

    function save(event) {
        event.preventDefault();
        const veterinario = {
            id: $('#id').val(),
            nome: $('#nome').val(),
            crmv: $('#crmv').val(),
            especialidade_id: $('#especialidade_id').val(),
        }

        if (veterinario.id == '') {
            $.post("/api/veterinario", veterinario, function (data) {
                row = getTableRow(data);
                $('#datatable>tbody').append(row);
            });
        } else {
            $.ajax({
                type: 'PUT',
                url: '/api/veterinario/' + veterinario.id,
                context: this,
                data: veterinario,
                success: function (data) {
                    rows = $("#datatable>tbody>tr");
                    e = rows.filter(function(i, e) {
                        return e.cells[0].textContent == veterinario.id;
                    });
                    if (e) {
                        e[0].cells[1].textContent = veterinario.nome;
                        e[0].cells[2].textContent = veterinario.crmv;
                        e[0].cells[3].textContent = veterinario.especialidade_id;
                    }
                }
            });
        }

        $('#createModal').modal('hide');
    }

    function show(id) {
        $('#showModal').modal().find('.modal-body').html('');
        $.getJSON('/api/veterinario/' + id, function (veterinario) {
            $('#showModal').modal().find('.modal-body').append(`<strong>ID: </strong>${veterinario.id}<br>`);
            $('#showModal').modal().find('.modal-body').append(`<strong>NOME: </strong>${veterinario.nome}<br>`);
            $('#showModal').modal().find('.modal-body').append(`<strong>CRMV: </strong>${veterinario.crmv}<br>`);
            $('#showModal').modal().find('.modal-body').append(`<strong>ESPECIALIDADE: </strong>${veterinario.especialidade.nome}<br>`);
            $('#showModal').modal('show')
        });
    }

    function edit(id) {
        $('#createModal').modal().find('.modal-title').text("Editar Veterinário");

        $.getJSON('/api/veterinario/' + id, function (veterinario) {
            $("#id").val(veterinario.id);
            $("#nome").val(veterinario.nome);
            $("#crmv").val(veterinario.crmv);
            $("#especialidade_id").val(veterinario.especialidade_id);

            $('#createModal').modal('show');
        });
    }

    function remove(id, nome) {
        $('#removeModal').modal().find('.modal-body').html('');
        $('#removeModal').modal().find('.modal-body').append(`Deseja remover o veterinario ${nome}?`);
        $('#id_remove').val(id);
        $('#removeModal').modal('show');
    }

    function del() {
        var id = $('#id_remove').val();
        $.ajax({
            type: 'DELETE',
            url: '/api/veterinario/' + id,
            context: this,
            success: function () {
                rows = $('#datatable>tbody>tr');
                e = rows.filter(function(i, e) {
                    return e.cells[0].textContent == id;
                });
                if (e) {
                    e.remove();
                }
            }
        });
        $('#removeModal').modal('hide');
    }

    function getTableRow(veterinario) {
        return '<tr>' +
            `<th class="text-center">${veterinario.id}</th>` +
            `<td>${veterinario.nome}</td>` +
            `<td>${veterinario.crmv}</td>` +
            `<td>${veterinario.especialidade.nome}</td>` +
            '<td class="text-center">' +
            `<a nohref class="link-secondary mx-1" style="cursor: pointer;" onclick="show(${veterinario.id})"><i class="bi bi-info-circle-fill"></i></a>` +
            `<a nohref class="link-primary mx-1" style="cursor: pointer;" onclick="edit(${veterinario.id})"><i class="bi bi-pencil-fill"></i></a>` +
            `<a nohref class="link-danger mx-1" style="cursor: pointer;" onclick="remove(${veterinario.id}, '${veterinario.nome}')"><i class="bi bi-trash-fill"></i></a>` +
            '</td>' +
            '</tr>';
    }
</script>
@endsection
