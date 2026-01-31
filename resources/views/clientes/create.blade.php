@extends('layouts.app')

@section('title', 'Nuevo Cliente - ClubPension')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus me-2"></i>Nuevo Cliente
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf
            
            <h5 class="mb-3 text-primary">
                <i class="fas fa-info-circle me-2"></i> Datos Básicos del Cliente
            </h5>
            
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <label for="tipo_cliente" class="form-label">
                        Tipo de Cliente <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('tipo_cliente') is-invalid @enderror" 
                            id="tipo_cliente" 
                            name="tipo_cliente" 
                            required>
                        <option value="">Seleccionar...</option>
                        <option value="C" {{ old('tipo_cliente') == 'C' ? 'selected' : '' }}>Cliente</option>
                        <option value="P" {{ old('tipo_cliente') == 'P' ? 'selected' : '' }}>Prospecto</option>
                        <option value="S" {{ old('tipo_cliente') == 'S' ? 'selected' : '' }}>Suspendido</option>
                        <option value="B" {{ old('tipo_cliente') == 'B' ? 'selected' : '' }}>Baja</option>
                        <option value="I" {{ old('tipo_cliente') == 'I' ? 'selected' : '' }}>Imposible</option>
                    </select>
                    @error('tipo_cliente')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="estatus" class="form-label">
                        Estatus <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('estatus') is-invalid @enderror" 
                            id="estatus" 
                            name="estatus" 
                            required>
                        <option value="">Seleccionar...</option>
                        <option value="Activo" {{ old('estatus') == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Suspendido" {{ old('estatus') == 'Suspendido' ? 'selected' : '' }}>Suspendido</option>
                        <option value="Terminado" {{ old('estatus') == 'Terminado' ? 'selected' : '' }}>Terminado</option>
                        <option value="Baja" {{ old('estatus') == 'Baja' ? 'selected' : '' }}>Baja</option>
                    </select>
                    @error('estatus')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="fecha_contrato" class="form-label">
                        Fecha Contrato <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           class="form-control @error('fecha_contrato') is-invalid @enderror" 
                           id="fecha_contrato" 
                           name="fecha_contrato" 
                           value="{{ old('fecha_contrato') }}" 
                           required>
                    @error('fecha_contrato')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">(Se guardará automáticamente la fecha de captura)</small>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="cliente_referidor_id" class="form-label">
                        Referencia <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('cliente_referidor_id') is-invalid @enderror" 
                            id="cliente_referidor_id" 
                            name="cliente_referidor_id">
                        <option value="">N/A</option>
                        @foreach($clientesReferencia ?? [] as $clienteRef)
                            <option value="{{ $clienteRef->id }}" {{ old('cliente_referidor_id') == $clienteRef->id ? 'selected' : '' }}>
                                {{ $clienteRef->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_referidor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <h5 class="mb-3 text-primary">
                <i class="fas fa-user me-2"></i> Datos Personales
            </h5>
            
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <label for="nombre" class="form-label">
                        Nombre <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre') }}" 
                           required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="apellido_paterno" class="form-label">
                        Apellido Paterno <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('apellido_paterno') is-invalid @enderror" 
                           id="apellido_paterno" 
                           name="apellido_paterno" 
                           value="{{ old('apellido_paterno') }}" 
                           required>
                    @error('apellido_paterno')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="apellido_materno" class="form-label">
                        Apellido Materno <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('apellido_materno') is-invalid @enderror" 
                           id="apellido_materno" 
                           name="apellido_materno" 
                           value="{{ old('apellido_materno') }}" 
                           required>
                    @error('apellido_materno')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="fecha_nacimiento" class="form-label">
                        Fecha de Nacimiento <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                           id="fecha_nacimiento" 
                           name="fecha_nacimiento" 
                           value="{{ old('fecha_nacimiento') }}" 
                           required>
                    @error('fecha_nacimiento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Crear Cliente
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <i class="fas fa-info-circle me-2"></i> Información importante
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Tipos de Cliente:</h6>
                <ul class="list-unstyled">
                    <li><strong>C:</strong> Cliente activo</li>
                    <li><strong>P:</strong> Prospecto (en proceso)</li>
                    <li><strong>S:</strong> Suspendido temporalmente</li>
                    <li><strong>B:</strong> Baja definitiva</li>
                    <li><strong>I:</strong> Imposible continuar</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Campos obligatorios:</h6>
                <ul class="list-unstyled">
                    <li><span class="text-danger">*</span> Tipo de Cliente</li>
                    <li><span class="text-danger">*</span> Estatus</li>
                    <li><span class="text-danger">*</span> Nombre completo</li>
                    <li><span class="text-danger">*</span> Apellidos</li>
                    <li><span class="text-danger">*</span> Fecha de Nacimiento</li>
                    <li><span class="text-danger">*</span> Fecha Contrato</li>
                    <li><span class="text-danger">*</span> Referencia (N/A si no aplica)</li>
                </ul>
                <p class="text-muted mb-0">
                    <small>El número de cliente se generará automáticamente después de guardar</small>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaContratoInput = document.getElementById('fecha_contrato');
    
    if (fechaContratoInput && !fechaContratoInput.value) {
        const today = new Date().toISOString().split('T')[0];
        fechaContratoInput.value = today;
    }
    
    const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
    if (fechaNacimientoInput) {
        fechaNacimientoInput.addEventListener('change', function() {
            if (this.value) {
                const fechaNacimiento = new Date(this.value);
                const hoy = new Date();
                let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                const mes = hoy.getMonth() - fechaNacimiento.getMonth();
                
                if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                    edad--;
                }
                
                if (edad < 18) {
                    alert('¡Advertencia! El cliente es menor de edad (' + edad + ' años)');
                }
            }
        });
    }
});
</script>
@endpush
@endsection
