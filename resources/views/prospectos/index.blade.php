@extends('layouts.app')

@section('content')
<div class="container-fluid">

{{-- Mensajes de error --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif


    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-people"></i> Prospectos</h4>
        <a href="{{ route('prospectos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Agregar prospecto
        </a>
    </div>

    {{-- Nota de ordenamiento --}}
    <div class="mb-2 text-muted">
        Haz click en los encabezados para ordenar por cualquier columna.
    </div>

    {{-- Filtro por estatus --}}
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <select name="estatus" class="form-select" onchange="this.form.submit()">
                    <option value="" {{ $estatusSeleccionado === null ? 'selected' : '' }}>
                        -- Todos los estatus (excepto Convertidos) --
                    </option>
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
                        @php
                            $columns = [
                                'id' => 'ID',
                                'nombre' => 'Nombre',
                                'curp' => 'CURP',
                                'nss' => 'NSS',
                                'celular' => 'Celular',
                                'estatus_prospecto_id' => 'Estatus',
                                'acciones' => 'Acciones',
                                'created_at' => 'Fecha',
                                'convertido' => 'Convertido'
                            ];
                        @endphp

                        @foreach($columns as $col => $label)
                            <th>
                                @if(in_array($col, ['id','nombre','curp','nss','celular','estatus_prospecto_id','created_at']))
                                    @php
                                        $isSorted = ($sort == $col);
                                        $newDirection = ($isSorted && $direction == 'asc') ? 'desc' : 'asc';
                                    @endphp
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => $col, 'direction' => $newDirection]) }}" class="text-decoration-none text-dark">
                                        {{ $label }}
                                        @if($isSorted)
                                            @if($direction == 'asc')
                                                <i class="bi bi-arrow-up-short"></i>
                                            @else
                                                <i class="bi bi-arrow-down-short"></i>
                                            @endif
                                        @else
                                            <i class="bi bi-arrow-down-up"></i>
                                        @endif
                                    </a>
                                @else
                                    {{ $label }}
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                @forelse($prospectos as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->nombre }} {{ $p->apellido_paterno }} {{ $p->apellido_materno }}</td>
                        <td>{{ $p->curp }}</td>
                        <td>{{ $p->nss }}</td>
                        <td>{{ $p->celular }}</td>

                        {{-- Estatus --}}
                        <td>
                            <form method="POST" action="{{ route('prospectos.updateEstatus', $p) }}" class="d-inline">
                                @csrf @method('PUT')
                                <select name="estatus_prospecto_id" class="form-select form-select-sm estatus-select" {{ $p->convertido ? 'disabled' : '' }}>
                                    @foreach($estatus as $e)
                                        @if($e->nombre !== 'Convertido')
                                            <option value="{{ $e->id }}" {{ $p->estatus_prospecto_id == $e->id ? 'selected' : '' }}>
                                                {{ $e->nombre }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                        </td>

                        {{-- Acciones --}}
                        <td>
                            <button class="btn btn-sm btn-success" title="Guardar estatus" {{ $p->convertido ? 'disabled' : '' }}>
                                <i class="bi bi-save"></i>
                            </button>
                            </form>

                            {{-- Convertir --}}
                            <form method="POST" action="{{ route('prospectos.convertir', $p) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning btn-convertir" title="Convertir" {{ $p->convertido ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                        </td>

                        <td>{{ \Carbon\Carbon::parse($p->created_at)->translatedFormat('j-M-y') }}</td>

                        <td>
                            @if($p->convertido)
                                <span class="badge bg-success">Convertido</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No hay prospectos</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-2">
            {{ $prospectos->links() }}
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sombrear select si cambia el valor
    const selects = document.querySelectorAll('.estatus-select');
    selects.forEach(select => {
        const originalValue = select.value;
        select.addEventListener('change', function() {
            if (this.value !== originalValue) {
                this.style.backgroundColor = '#f8d7da';
            } else {
                this.style.backgroundColor = '';
            }
        });
    });

    // Confirmación al convertir
    const botonesConvertir = document.querySelectorAll('.btn-convertir');
    botonesConvertir.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if(!confirm('¿Convertir este prospecto en cliente?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush

@endsection
