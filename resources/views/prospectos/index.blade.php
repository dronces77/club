@extends('layouts.app')

@section('content')
<div class="container-fluid">

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
                            // Orden actualizado de encabezados
                            $columns = [
                                'nombre' => 'Nombre',
                                'curp' => 'CURP',
                                'nss' => 'NSS',
                                'celular' => 'Celular',
                                'estatus_prospecto_id' => 'Estatus',
                                'fecha_creacion' => 'Fecha',
                                'acciones' => 'Acciones',
                                'convertido' => 'Convertido'
                            ];
                        @endphp

                        @foreach($columns as $col => $label)
                            <th>
                                @if(in_array($col, ['nombre','curp','nss','celular','fecha_creacion']))
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
                        <td>{{ $p->nombre }} {{ $p->apellido_paterno }} {{ $p->apellido_materno }}</td>
                        <td>{{ $p->curp }}</td>
                        <td>{{ $p->nss }}</td>
                        <td>{{ $p->celular }}</td>

                        <td>
                            <form method="POST" action="{{ route('prospectos.updateEstatus', $p) }}">
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

                        <td>{{ \Carbon\Carbon::parse($p->fecha_creacion)->format('Y-m-d') }}</td>

                        <td class="text-center">
                                <button class="btn btn-sm btn-success" title="Guardar estatus" {{ $p->convertido ? 'disabled' : '' }}>
                                    <i class="bi bi-save"></i>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('prospectos.convertir', $p) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-warning"
                                    onclick="return confirm('¿Convertir este prospecto en cliente?')"
                                    title="Convertir"
                                    {{ $p->convertido ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                        </td>

                        <td>
                            @if($p->convertido)
                                <span class="badge bg-success">Convertido</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay prospectos</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Script para sombrear select al cambiar valor --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('.estatus-select');
        selects.forEach(select => {
            const originalValue = select.value;
            select.addEventListener('change', function() {
                if (this.value !== originalValue) {
                    this.style.backgroundColor = '#f8d7da'; // rojo cálido
                } else {
                    this.style.backgroundColor = '';
                }
            });
        });
    });
</script>
@endpush

@endsection
