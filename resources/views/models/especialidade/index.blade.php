@extends('layouts.app', ['page' => 'especialidade', 'title' => 'Cadastro de Especialidades', 'icon' => 'clipboard'])

@section('content')
<form class="row gx-2 mb-2" action="{{ route('especialidade.index') }}" method="POST" autocomplete="off">
    @csrf
    <div class="col-12 col-md-3">
        <button class="btn btn-primary w-100" type="button" onclick="create()">Cadastrar Nova Especialidade</button>
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
            <th>DESCRIÇÃO</th>
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
                    <label class="form-label mb-0" for="nome">Nome</label>
                    <input class="form-control" type="text" name="nome" id="nome">
                </div>
                <div class="mb-1">
                    <label class="form-label mb-0" for="descricao">Descrição</label>
                    <input class="form-control" type="text" name="descricao" id="descricao">
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
                <h5 class="modal-title" id="removeModalLabel">Remover Especialidade</h5>
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
                <h5 class="modal-title" id="showModalLabel">Informações da Especialidade</h5>
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
    const especialidades = JSON.parse('{!! json_encode($especialidades) !!}');
    for (const c of especialidades) {
        row = getTableRow(c);
        $('#datatable>tbody').append(row);
    }

    $.ajaxSetup({
        'X-CSRF-TOKEN': "{{ csrf_token() }}",
    });

    function create() {
        $('#createModal').modal().find('.modal-title').text("Novo especialidade");

        $("#id").val('');
        $("#nome").val('');
        $("#descricao").val('');

        $('#createModal').modal('show');
    }

    function save(event) {
        event.preventDefault();
        const especialidade = {
            id: $('#id').val(),
            nome: $('#nome').val(),
            descricao: $('#descricao').val()
        }

        if (especialidade.id == '') {
            $.post("/api/especialidade", especialidade, function (data) {
                row = getTableRow(data);
                $('#datatable>tbody').append(row);
            });
        } else {
            $.ajax({
                type: 'PUT',
                url: '/api/especialidade/' + especialidade.id,
                context: this,
                data: especialidade,
                success: function (data) {
                    rows = $("#datatable>tbody>tr");
                    e = rows.filter(function(i, e) {
                        return e.cells[0].textContent == especialidade.id;
                    });
                    if (e) {
                        e[0].cells[1].textContent = especialidade.nome;
                        e[0].cells[2].textContent = especialidade.descricao;
                    }
                }
            });
        }

        $('#createModal').modal('hide');
    }

    function show(id) {
        $('#showModal').modal().find('.modal-body').html('');
        $.getJSON('/api/especialidade/' + id, function (especialidade) {
            $('#showModal').modal().find('.modal-body').append(`<strong>ID: </strong>${especialidade.id}<br>`);
            $('#showModal').modal().find('.modal-body').append(`<strong>NOME: </strong>${especialidade.nome}<br>`);
            $('#showModal').modal().find('.modal-body').append(`<strong>DESCRIÇÃO: </strong>${especialidade.descricao}<br>`);
            $('#showModal').modal('show')
        });
    }

    function edit(id) {
        $('#createModal').modal().find('.modal-title').text("Editar Especialidade");

        $.getJSON('/api/especialidade/' + id, function (especialidade) {
            $("#id").val(especialidade.id);
            $("#nome").val(especialidade.nome);
            $("#descricao").val(especialidade.descricao);

            $('#createModal').modal('show');
        });
    }

    function remove(id, nome) {
        $('#removeModal').modal().find('.modal-body').html('');
        $('#removeModal').modal().find('.modal-body').append(`Deseja remover a especialidade ${nome}?`);
        $('#id_remove').val(id);
        $('#removeModal').modal('show');
    }

    function del() {
        var id = $('#id_remove').val();
        $.ajax({
            type: 'DELETE',
            url: '/api/especialidade/' + id,
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

    function getTableRow(especialidade) {
        return '<tr>' +
            `<th class="text-center">${especialidade.id}</th>` +
            `<td>${especialidade.nome}</td>` +
            `<td>${especialidade.descricao}</td>` +
            '<td class="text-center">' +
            `<a nohref class="link-secondary mx-1" style="cursor: pointer;" onclick="show(${especialidade.id})"><i class="bi bi-info-circle-fill"></i></a>` +
            `<a nohref class="link-primary mx-1" style="cursor: pointer;" onclick="edit(${especialidade.id})"><i class="bi bi-pencil-fill"></i></a>` +
            `<a nohref class="link-danger mx-1" style="cursor: pointer;" onclick="remove(${especialidade.id}, '${especialidade.nome}')"><i class="bi bi-trash-fill"></i></a>` +
            '</td>' +
            '</tr>';
    }
</script>
@endsection
