@extends('layouts.app', ['page' => 'cliente', 'title' => 'Cadastrar Novo Cliente', 'icon' => 'person-circle'])

@section('content')
<form class="row g-2" action="@isset($cliente){{ route('cliente.update', ['cliente' => $cliente['id']]) }}@else{{ route('cliente.store') }}@endisset" method="POST" autocomplete="off">
    @csrf
    @isset($cliente)
        @method('PUT')
    @endisset
    <div class="col-12">
        <label class="form-label mb-0" for="nome">Nome Completo</label>
        <input class="form-control @error('nome'){{ 'is-invalid' }}@enderror" type="nome" id="nome" name="nome" value="{{ $cliente['nome'] ?? old('nome') }}" required autofocus>
        @error('nome')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-md-8">
        <label class="form-label mb-0" for="email">Endereço de Email</label>
        <input class="form-control @error('email'){{ 'is-invalid' }}@enderror" type="email" id="email" name="email" value="{{ $cliente['email'] ?? old('email') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label mb-0" for="telefone">Nº de Telefone</label>
        <input class="form-control @error('telefone'){{ 'is-invalid' }}@enderror" type="telefone" id="telefone" name="telefone" value="{{ $cliente['telefone'] ?? old('telefone') }}" required>
        @error('telefone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-md-4">
        <a class="btn btn-danger w-100" href="{{ route('cliente.index') }}">Voltar \ Cancelar</a>
    </div>
    <div class="col-6 col-md-8">
        <button class="btn btn-success w-100" type="submit">Cadastrar \ Salvar</button>
    </div>
</form>
@endsection
