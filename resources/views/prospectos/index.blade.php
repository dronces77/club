@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-people"></i> Prospectos</h4>

        <a href="{{ route('prospectos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Agregar prospecto
        </a>
    </div>

    {{-- Filtro por estatus --}}
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <select name="estatus" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Todos los estatus --</option>
                    @foreach($estatus as $e)
                        <option value="{{ $e->id }}" {{ $estatusSeleccionado == $e->id ? 'selected' : '' }}>
                            {{ $e->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>CURP</th>
                        <th>NSS</th>
                        <th>Celular</th>
                        <th>Estatus</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($prospectos as $p)
                    <tr>
                        <td>{{ $p->nombre }} {{ $p->apellido_paterno }} {{ $p->apellido_materno }}</td>
                        <td>{{ $p->curp }}</td>
                        <td>{{ $p->nss }}</td>
                        <td>{{ $p->celular }}</td>

                        <td>
                            @if(!$p->convertido)
                            <form method="POST" action="{{ route('prospectos.updateEstatus', $p) }}">
                                @csrf @method('PUT')
                                <select name="estatus_prospecto_id" class="form-select form-select-sm">
                                    @foreach($estatus as $e)
                                        <option value="{{ $e->id }}" {{ $p->estatus_prospecto_id == $e->id ? 'selected' : '' }}>
                                            {{ $e->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                        </td>

                        <td>{{ \Carbon\Carbon::parse($p->fecha_creacion)->format('Y-m-d') }}</td>

                        <td class="text-center">
                                <button class="btn btn-sm btn-success" title="Guardar estatus">
                                    <i class="bi bi-save"></i>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('prospectos.convertir', $p) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-warning"
                                    onclick="return confirm('¿Convertir este prospecto en cliente?')"
                                    title="Convertir">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                        </td>
                        @else
                        <td><span class="badge bg-success">Convertido</span></td>
                        <td>{{ \Carbon\Carbon::parse($p->fecha_creacion)->format('Y-m-d') }}</td>
                        <td class="text-center text-muted">—</td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay prospectos</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
