@extends('layouts.app', ['page' => 'veterinario', 'title' => 'Cadastrar Novo Veterinário', 'icon' => 'file-earmark-person'])

@section('content')
<form class="row g-2" action="@isset($veterinario){{ route('veterinario.update', ['veterinario' => $veterinario['id']]) }}@else{{ route('veterinario.store') }}@endisset" method="POST" autocomplete="off">
    @csrf
    @isset($veterinario)
        @method('PUT')
    @endisset
    <div class="col-12">
        <label class="form-label mb-0" for="nome">Nome Completo</label>
        <input class="form-control @error('nome'){{ 'is-invalid' }}@enderror" type="nome" id="nome" name="nome" value="{{ $veterinario['nome'] ?? old('nome') }}" required autofocus>
        @error('nome')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label mb-0" for="crmv">CRMV</label>
        <input class="form-control @error('crmv'){{ 'is-invalid' }}@enderror" type="crmv" id="crmv" name="crmv" value="{{ $veterinario['crmv'] ?? old('crmv') }}" required>
        @error('crmv')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12 col-md-6">
        <label class="form-label mb-0" for="especialidade_id">Especialidade</label>
        <select class="form-select" name="especialidade_id" id="especialidade_id" required>
            <option hidden disabled selected value>Selecione uma especialidade...</option>
            @foreach (\App\Models\Especialidade::all() as $esp)
            <option value="{{ $esp->id }}" @if($esp->id == ($veterinario->especialidade_id ?? old('especialidade_id'))){{ 'selected' }}@endif>{{ $esp->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-md-4">
        <a class="btn btn-danger w-100" href="{{ route('veterinario.index') }}">Voltar \ Cancelar</a>
    </div>
    <div class="col-6 col-md-8">
        <button class="btn btn-success w-100" type="submit">Cadastrar \ Salvar</button>
    </div>
</form>
@endsection
