<form class="row gx-2 mb-2" action="{{ route($model.'.index') }}" method="POST" autocomplete="off">
    @csrf
    <div class="col-12 col-md-3">
        <a class="btn btn-primary w-100" href="{{ route($model.'.create') }}">{{ $btnCreateText }}</a>
    </div>
    <div class="col-11 col-md-8">
        <input class="form-control" type="text" name="search" id="search" placeholder="Buscar" required>
    </div>
    <div class="col-1">
        <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search"></i></button>
    </div>
</form>
