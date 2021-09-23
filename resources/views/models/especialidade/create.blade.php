@extends('layouts.app', ['page' => 'especialidade', 'title' => 'Cadastrar Nova Especialidade', 'icon' => 'clipboard'])

@section('content')
<form class="row g-2" action="@isset($especialidade){{ route('especialidade.update', ['especialidade' => $especialidade['id']]) }}@else{{ route('especialidade.store') }}@endisset" method="POST" autocomplete="off">
    @csrf
    @isset($especialidade)
        @method('PUT')
    @endisset
    <div class="col-12">
        <label class="form-label mb-0" for="nome">Nome</label>
        <input class="form-control @error('nome'){{ 'is-invalid' }}@enderror" type="nome" id="nome" name="nome" value="{{ $especialidade['nome'] ?? old('nome') }}" required autofocus>
        @error('nome')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label mb-0" for="descricao">Descrição</label>
        <textarea class="form-control @error('descricao'){{ 'is-invalid' }}@enderror" name="descricao" id="descricao" rows="3" required>{{ $especialidade['descricao'] ?? old('descricao') }}</textarea>
        @error('descricao')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-6 col-md-4">
        <a class="btn btn-danger w-100" href="{{ route('especialidade.index') }}">Voltar \ Cancelar</a>
    </div>
    <div class="col-6 col-md-8">
        <button class="btn btn-success w-100" type="submit">Cadastrar \ Salvar</button>
    </div>
</form>
@endsection
