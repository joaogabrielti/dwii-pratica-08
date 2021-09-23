@extends('layouts.app', ['page' => 'cliente', 'title' => 'Cadastro de Clientes', 'icon' => 'person-circle'])

@section('content')
<form class="row gx-2 mb-2" action="{{ route('cliente.index') }}" method="POST" autocomplete="off">
    @csrf
    <div class="col-12 col-md-3">
        <button class="btn btn-primary w-100" type="button" onclick="create()">Cadastrar Novo Cliente</button>
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
            <th>EMAIL</th>
            <th>TELEFONE</th>
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
                    <label class="form-label mb-0" for="email">Endereço de Email</label>
                    <input class="form-control" type="text" name="email" id="email">
                </div>
                <div class="mb-1">
                    <label class="form-label mb-0" for="telefone">Nº de Telefone</label>
                    <input class="form-control" type="text" name="telefone" id="telefone">
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
                <h5 class="modal-title" id="removeModalLabel">Remover Cliente</h5>
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
                <h5 class="modal-title" id="showModalLabel">Informações do Cliente</h5>
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
    const clientes = JSON.parse('{!! json_encode($clientes) !!}');
    for (const c of clientes) {
        row = getTableRow(c);
        $('#datatable>tbody').append(row);
    }

    $.ajaxSetup({
        'X-CSRF-TOKEN': "{{ csrf_token() }}",
    });

    function create() {
        $('#createModal').modal().find('.modal-title').text("Novo Cliente");

        $("#id").val('');
        $("#nome").val('');
        $("#email").val('');
        $("#telefone").val('');

        $('#createModal').modal('show');
    }

    function save(event) {
        event.preventDefault();
        const cliente = {
            id: $('#id').val(),
            nome: $('#nome').val(),
            email: $('#email').val(),
            telefone: $('#telefone').val(),
        }

        if (cliente.id == '') {
            $.post("/api/cliente", cliente, function (data) {
                row = getTableRow(data);
                $('#datatable>tbody').append(row);
            });
        } else {
            $.ajax({
                type: 'PUT',
                url: '/api/cliente/' + cliente.id,
                context: this,
                data: cliente,
                success: function (data) {
                    rows = $("#datatable>tbody>tr");
                    e = rows.filter(function(i, e) {
                        return e.cells[0].textContent == cliente.id;
                    });
                    if (e) {
                        e[0].cells[1].textContent = cliente.nome;
                        e[0].cells[2].textContent = cliente.email;
                        e[0].cells[3].textContent = cliente.telefone;
                    }
                }
            });
        }

        $('#createModal').modal('hide');
    }

    function show(id) {
        $('#showModal').modal().find('.modal-body').html('');
        $.getJSON('/api/cliente/' + id, function (cliente) {
            $('#showModal').modal().find('.modal-body').append(`<strong>ID: </strong>${cliente.id}<br>`);
            $('#showModal').modal().find('.modal-body').append(`<strong>NOME: </strong>${cliente.nome}<br>`);
            $('#showModal').modal().find('.modal-body').append(`<strong>EMAIL: </strong>${cliente.email}<br>`);
            $('#showModal').modal().find('.modal-body').append(`<strong>TELEFONE: </strong>${cliente.telefone}<br>`);
            $('#showModal').modal('show')
        });
    }

    function edit(id) {
        $('#createModal').modal().find('.modal-title').text("Editar Cliente");

        $.getJSON('/api/cliente/' + id, function (cliente) {
            $("#id").val(cliente.id);
            $("#nome").val(cliente.nome);
            $("#email").val(cliente.email);
            $("#telefone").val(cliente.telefone);

            $('#createModal').modal('show');
        });
    }

    function remove(id, nome) {
        $('#removeModal').modal().find('.modal-body').html('');
        $('#removeModal').modal().find('.modal-body').append(`Deseja remover o cliente ${nome}?`);
        $('#id_remove').val(id);
        $('#removeModal').modal('show');
    }

    function del() {
        var id = $('#id_remove').val();
        $.ajax({
            type: 'DELETE',
            url: '/api/cliente/' + id,
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

    function getTableRow(cliente) {
        return '<tr>' +
            `<th class="text-center">${cliente.id}</th>` +
            `<td>${cliente.nome}</td>` +
            `<td>${cliente.email}</td>` +
            `<td>${cliente.telefone}</td>` +
            '<td class="text-center">' +
            `<a nohref class="link-secondary mx-1" style="cursor: pointer;" onclick="show(${cliente.id})"><i class="bi bi-info-circle-fill"></i></a>` +
            `<a nohref class="link-primary mx-1" style="cursor: pointer;" onclick="edit(${cliente.id})"><i class="bi bi-pencil-fill"></i></a>` +
            `<a nohref class="link-danger mx-1" style="cursor: pointer;" onclick="remove(${cliente.id}, '${cliente.nome}')"><i class="bi bi-trash-fill"></i></a>` +
            '</td>' +
            '</tr>';
    }
</script>
@endsection
