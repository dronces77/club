@extends('layouts.app')

@section('title', 'Editar Cliente - ClubPension')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>Editar Cliente
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
            <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-info">
                <i class="fas fa-eye me-1"></i> Ver
            </a>
        </div>
    </div>
</div>

<!-- Información del cliente -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Información actual:</h6>
                <p class="mb-1"><strong>No. Cliente:</strong> {{ $cliente->no_cliente ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Cliente desde:</strong> {{ $cliente->creado_en ? $cliente->creado_en->format('d/m/Y') : 'N/A' }}</p>
                <p class="mb-0"><strong>Última actualización:</strong> {{ $cliente->actualizado_en ? $cliente->actualizado_en->format('d/m/Y H:i') : 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h6>Estatus actual:</h6>
                @php
                    $badgeClass = 'badge-pendiente';
                    if($cliente->estatus == 'Activo') $badgeClass = 'badge-activo';
                    elseif($cliente->estatus == 'Suspendido') $badgeClass = 'badge-suspendido';
                @endphp
                <span class="badge-estatus {{ $badgeClass }}">
                    {{ $cliente->estatus }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Formulario de edición -->
<div class="card">
    <div class="card-body">
        <form action="{{ route('clientes.update', $cliente) }}" method="POST">
            @include('clientes._form')
        </form>
    </div>
</div>

<!-- Historial de cambios (futuro) -->
<div class="card mt-4">
    <div class="card-header">
        <i class="fas fa-history me-2"></i> Datos económicos (opcional)
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="pension_default" class="form-label">Pensión Default</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" 
                           step="0.01" 
                           class="form-control" 
                           id="pension_default" 
                           name="pension_default"
                           value="{{ old('pension_default', $cliente->pension_default ?? '') }}">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="pension_normal" class="form-label">Pensión Normal</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" 
                           step="0.01" 
                           class="form-control" 
                           id="pension_normal" 
                           name="pension_normal"
                           value="{{ old('pension_normal', $cliente->pension_normal ?? '') }}">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="comision" class="form-label">Comisión</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" 
                           step="0.01" 
                           class="form-control" 
                           id="comision" 
                           name="comision"
                           value="{{ old('comision', $cliente->comision ?? '') }}">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="honorarios" class="form-label">Honorarios</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" 
                           step="0.01" 
                           class="form-control" 
                           id="honorarios" 
                           name="honorarios"
                           value="{{ old('honorarios', $cliente->honorarios ?? '') }}">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fecha_alta" class="form-label">Fecha Alta</label>
                <input type="date" 
                       class="form-control" 
                       id="fecha_alta" 
                       name="fecha_alta"
                       value="{{ old('fecha_alta', isset($cliente->fecha_alta) ? $cliente->fecha_alta->format('Y-m-d') : '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label for="fecha_baja" class="form-label">Fecha Baja</label>
                <input type="date" 
                       class="form-control" 
                       id="fecha_baja" 
                       name="fecha_baja"
                       value="{{ old('fecha_baja', isset($cliente->fecha_baja) ? $cliente->fecha_baja->format('Y-m-d') : '') }}">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar régimenes correspondientes al instituto actual
    const institutoSelect = document.getElementById('instituto_id');
    const regimenSelect = document.getElementById('regimen_id');
    const regimenesOriginales = @json($regimenes);
    
    function cargarRegimenes(institutoId) {
        // Limpiar opciones excepto la primera
        while (regimenSelect.options.length > 1) {
            regimenSelect.remove(1);
        }
        
        if (institutoId) {
            const regimenesFiltrados = regimenesOriginales.filter(r => r.instituto_id == institutoId);
            
            regimenesFiltrados.forEach(regimen => {
                const option = document.createElement('option');
                option.value = regimen.id;
                option.textContent = `${regimen.nombre}`;
                // Seleccionar si es el régimen actual del cliente
                if (regimen.id == @json($cliente->regimen_id)) {
                    option.selected = true;
                }
                regimenSelect.appendChild(option);
            });
            
            regimenSelect.disabled = false;
        } else {
            regimenSelect.disabled = true;
        }
    }
    
    if (institutoSelect && regimenSelect) {
        // Cargar al inicio
        cargarRegimenes(institutoSelect.value);
        
        // Actualizar al cambiar institución
        institutoSelect.addEventListener('change', function() {
            cargarRegimenes(this.value);
        });
    }
});
</script>
@endpush
@endsection
