@extends('layouts.app')

@section('title', 'Nuevo Cliente - ClubPension')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus me-2"></i>Nuevo Prospecto
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('prospectos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver a Prospectos
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <!-- Información importante -->
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Importante:</strong> Todos los registros nuevos entran como <strong>Prospectos</strong>. 
            Después podrás convertirlos a Clientes desde la vista de Prospectos.
        </div>
        
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf
            
            <!-- Campos ocultos para valores por defecto -->
            <input type="hidden" name="tipo_cliente" value="P">
            <input type="hidden" name="estatus" value="">
            
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
                        Apellido Paterno
                    </label>
                    <input type="text" 
                           class="form-control @error('apellido_paterno') is-invalid @enderror" 
                           id="apellido_paterno" 
                           name="apellido_paterno" 
                           value="{{ old('apellido_paterno') }}">
                    @error('apellido_paterno')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="apellido_materno" class="form-label">
                        Apellido Materno
                    </label>
                    <input type="text" 
                           class="form-control @error('apellido_materno') is-invalid @enderror" 
                           id="apellido_materno" 
                           name="apellido_materno" 
                           value="{{ old('apellido_materno') }}">
                    @error('apellido_materno')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="fecha_nacimiento" class="form-label">
                        Fecha de Nacimiento
                    </label>
                    <input type="date" 
                           class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                           id="fecha_nacimiento" 
                           name="fecha_nacimiento" 
                           value="{{ old('fecha_nacimiento') }}">
                    @error('fecha_nacimiento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="fecha_contrato" class="form-label">
                        Fecha Contrato
                    </label>
                    <input type="date" 
                           class="form-control @error('fecha_contrato') is-invalid @enderror" 
                           id="fecha_contrato" 
                           name="fecha_contrato" 
                           value="{{ old('fecha_contrato') }}">
                    @error('fecha_contrato')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="cliente_referidor_id" class="form-label">
                        Referencia
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
                <i class="fas fa-address-card me-2"></i> Identificación y Contacto
            </h5>
            
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="curp" class="form-label">
                        CURP
                    </label>
                    <input type="text" 
                           class="form-control @error('curp') is-invalid @enderror" 
                           id="curp" 
                           name="curp" 
                           value="{{ old('curp') }}"
                           maxlength="18"
                           placeholder="Ej: GOFV681210HCHRRL00">
                    @error('curp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="celular1" class="form-label">
                        Celular Principal
                    </label>
                    <input type="tel" 
                           class="form-control @error('celular1') is-invalid @enderror" 
                           id="celular1" 
                           name="celular1" 
                           value="{{ old('celular1') }}"
                           placeholder="Ej: 5551234567">
                    @error('celular1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Crear Prospecto
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <i class="fas fa-info-circle me-2"></i> Información del proceso
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Flujo de trabajo:</h6>
                <ol>
                    <li><strong>Paso 1:</strong> Crear el registro como Prospecto</li>
                    <li><strong>Paso 2:</strong> Ir a la vista de Prospectos</li>
                    <li><strong>Paso 3:</strong> Convertir a Cliente cuando esté listo</li>
                    <li><strong>Paso 4:</strong> El sistema generará automáticamente:
                        <ul>
                            <li>Número de cliente único</li>
                            <li>Estatus "Activo"</li>
                            <li>Fecha de contrato (si no se especificó)</li>
                        </ul>
                    </li>
                </ol>
            </div>
            <div class="col-md-6">
                <h6>Campos requeridos:</h6>
                <ul class="list-unstyled">
                    <li><span class="text-danger">*</span> Nombre (obligatorio)</li>
                    <li>Apellidos (opcionales)</li>
                    <li>Fecha de Nacimiento (opcional)</li>
                    <li>CURP (opcional)</li>
                    <li>Celular (opcional)</li>
                </ul>
                <p class="text-muted mb-0">
                    <small>Los campos institucionales se llenan cuando se convierta a Cliente</small>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de CURP (convertir a mayúsculas)
    const curpInput = document.getElementById('curp');
    if (curpInput) {
        curpInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Validación de celular (solo números)
    const celularInput = document.getElementById('celular1');
    if (celularInput) {
        celularInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 10);
        });
    }
    
    // Establecer fecha de contrato por defecto
    const fechaContratoInput = document.getElementById('fecha_contrato');
    if (fechaContratoInput && !fechaContratoInput.value) {
        const today = new Date().toISOString().split('T')[0];
        fechaContratoInput.value = today;
    }
    
    // Calcular edad automáticamente
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