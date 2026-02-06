@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-pencil"></i> Editar Prospecto</h4>
        <a href="{{ route('prospectos.index') }}" class="btn btn-secondary">
            ← Volver a Prospectos
        </a>
    </div>

    <form method="POST" action="{{ route('prospectos.update', $prospecto->id) }}">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header text-primary">
                <i class="bi bi-person"></i> Datos Personales
            </div>
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control"
                        value="{{ old('nombre', $prospecto->nombre) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Paterno *</label>
                    <input type="text" name="apellido_paterno" class="form-control"
                        value="{{ old('apellido_paterno', $prospecto->apellido_paterno) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" name="apellido_materno" class="form-control"
                        value="{{ old('apellido_materno', $prospecto->apellido_materno) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">CURP *</label>
                    <input type="text" name="curp" maxlength="18"
                        class="form-control text-uppercase"
                        value="{{ old('curp', $prospecto->curp) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">NSS</label>
                    <input type="text" name="nss" maxlength="11"
                        class="form-control"
                        value="{{ old('nss', $prospecto->nss) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Celular</label>
                    <input type="text" name="celular" maxlength="13"
                        class="form-control"
                        value="{{ old('celular', $prospecto->celular) }}">
                </div>

            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
