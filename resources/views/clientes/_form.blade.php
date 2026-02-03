@csrf

@if(isset($cliente))
    @method('PUT')
@endif

{{-- Solo mostrar campos de tipo_cliente y estatus en EDICIÓN --}}
@if(isset($cliente) && $cliente->id)
    <div class="row mb-4">
        {{-- Tipo de Cliente (solo en edición) --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="tipo_cliente" class="form-label">Tipo de Cliente</label>
                <select class="form-control @error('tipo_cliente') is-invalid @enderror" 
                        id="tipo_cliente" name="tipo_cliente">
                    <option value="">Seleccione tipo</option>
                    <option value="P" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'P' ? 'selected' : '' }}>Prospecto</option>
                    <option value="C" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'C' ? 'selected' : '' }}>Cliente</option>
                    <option value="I" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'I' ? 'selected' : '' }}>Imposible</option>
                    <option value="B" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'B' ? 'selected' : '' }}>Baja</option>
                    <option value="S" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'S' ? 'selected' : '' }}>Suspendido</option>
                </select>
                @error('tipo_cliente')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                    @if($cliente->esCliente)
                        <span class="text-danger">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Ya es cliente, no puede cambiar este tipo.
                        </span>
                    @endif
                </small>
            </div>
        </div>
        
        {{-- Estatus (solo para Clientes, solo en edición) --}}
        <div class="col-md-6" id="estatus-field" style="{{ !$cliente->esCliente ? 'display: none;' : '' }}">
            <div class="form-group">
                <label for="estatus" class="form-label">Estatus</label>
                <select class="form-control @error('estatus') is-invalid @enderror" 
                        id="estatus" name="estatus" {{ $cliente->esCliente ? 'required' : '' }}>
                    <option value="">Seleccione estatus</option>
                    <option value="Activo" {{ old('estatus', $cliente->estatus ?? '') == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Suspendido" {{ old('estatus', $cliente->estatus ?? '') == 'Suspendido' ? 'selected' : '' }}>Suspendido</option>
                    <option value="Baja" {{ old('estatus', $cliente->estatus ?? '') == 'Baja' ? 'selected' : '' }}>Baja</option>
                    <option value="Terminado" {{ old('estatus', $cliente->estatus ?? '') == 'Terminado' ? 'selected' : '' }}>Terminado</option>
                </select>
                @error('estatus')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Solo aplica para Clientes</small>
            </div>
        </div>
    </div>
    
    {{-- Mostrar número de cliente si es cliente --}}
    @if($cliente->esCliente)
        <div class="alert alert-info mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-id-card fa-2x me-3"></i>
                <div>
                    <h6 class="mb-1"><strong>Cliente:</strong> {{ $cliente->no_cliente }}</h6>
                    <p class="mb-0">Este número es permanente e inmutable.</p>
                </div>
            </div>
        </div>
    @endif
@else
    {{-- Para creación nueva: mensaje informativo --}}
    <div class="alert alert-info mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle fa-2x me-3"></i>
            <div>
                <h6 class="mb-1">Nuevo Prospecto</h6>
                <p class="mb-0">
                    Se creará como <strong>Prospecto</strong> (sin número de cliente).<br>
                    Para convertirlo en Cliente, edítelo posteriormente en la vista de Prospectos.
                </p>
            </div>
        </div>
    </div>
@endif

<hr class="my-4">

<h5 class="mb-3">
    <i class="fas fa-user-circle me-2"></i>Datos Personales
</h5>

<div class="row">
    <!-- Nombre -->
    <div class="col-md-4 mb-3">
        <label for="nombre" class="form-label">Nombre *</label>
        <input type="text" 
               class="form-control @error('nombre') is-invalid @enderror" 
               id="nombre" 
               name="nombre" 
               value="{{ old('nombre', $cliente->nombre ?? '') }}" 
               required>
        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Apellido Paterno -->
    <div class="col-md-4 mb-3">
        <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
        <input type="text" 
               class="form-control @error('apellido_paterno') is-invalid @enderror" 
               id="apellido_paterno" 
               name="apellido_paterno" 
               value="{{ old('apellido_paterno', $cliente->apellido_paterno ?? '') }}">
        @error('apellido_paterno')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Apellido Materno -->
    <div class="col-md-4 mb-3">
        <label for="apellido_materno" class="form-label">Apellido Materno</label>
        <input type="text" 
               class="form-control @error('apellido_materno') is-invalid @enderror" 
               id="apellido_materno" 
               name="apellido_materno" 
               value="{{ old('apellido_materno', $cliente->apellido_materno ?? '') }}">
        @error('apellido_materno')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <!-- Fecha de Nacimiento -->
    <div class="col-md-4 mb-3">
        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
        <input type="date" 
               class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
               id="fecha_nacimiento" 
               name="fecha_nacimiento" 
               value="{{ old('fecha_nacimiento', isset($cliente->fecha_nacimiento) ? $cliente->fecha_nacimiento->format('Y-m-d') : '') }}">
        @error('fecha_nacimiento')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <!-- CURP -->
    <div class="col-md-4 mb-3">
        <label for="curp" class="form-label">CURP</label>
        <input type="text" 
               class="form-control @error('curp') is-invalid @enderror" 
               id="curp" 
               name="curp" 
               value="{{ old('curp', isset($cliente) && $cliente->curps->count() > 0 ? $cliente->curps->first()->curp : '') }}"
               pattern="[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[A-Z0-9]{2}"
               title="Formato: 4 letras, 6 números, sexo (H/M), 5 letras, 2 alfanuméricos">
        @error('curp')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <!-- Celular -->
    <div class="col-md-4 mb-3">
        <label for="celular" class="form-label">Celular</label>
        <input type="tel" 
               class="form-control @error('celular') is-invalid @enderror" 
               id="celular" 
               name="celular" 
               value="{{ old('celular', isset($cliente) && $cliente->contactos->where('tipo', 'celular')->count() > 0 ? $cliente->contactos->where('tipo', 'celular')->first()->valor : '') }}">
        @error('celular')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Campos adicionales solo para edición de clientes --}}
@if(isset($cliente) && $cliente->id && $cliente->esCliente)
    <hr class="my-4">
    <h5 class="mb-3">
        <i class="fas fa-building me-2"></i>Datos Institucionales (Opcional)
    </h5>
    
    <div class="row">
        <!-- Instituto -->
        <div class="col-md-4 mb-3">
            <label for="instituto_id" class="form-label">Institución</label>
            <select class="form-select @error('instituto_id') is-invalid @enderror" 
                    id="instituto_id" 
                    name="instituto_id">
                <option value="">Seleccionar...</option>
                @foreach($institutos ?? [] as $instituto)
                    <option value="{{ $instituto->id }}" 
                        {{ old('instituto_id', $cliente->instituto_id ?? '') == $instituto->id ? 'selected' : '' }}>
                        {{ $instituto->nombre }}
                    </option>
                @endforeach
            </select>
            @error('instituto_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Régimen -->
        <div class="col-md-4 mb-3">
            <label for="regimen_id" class="form-label">Régimen</label>
            <select class="form-select @error('regimen_id') is-invalid @enderror" 
                    id="regimen_id" 
                    name="regimen_id">
                <option value="">Seleccionar...</option>
                @foreach($regimenes ?? [] as $regimen)
                    <option value="{{ $regimen->id }}" 
                        {{ old('regimen_id', $cliente->regimen_id ?? '') == $regimen->id ? 'selected' : '' }}>
                        {{ $regimen->nombre }} ({{ $regimen->instituto->codigo ?? '' }})
                    </option>
                @endforeach
            </select>
            @error('regimen_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
@endif

<!-- Botones -->
<div class="d-flex justify-content-between mt-4">
    @if(isset($cliente) && $cliente->id)
        <a href="{{ $cliente->esCliente ? route('clientes.index') : route('prospectos.index') }}" 
           class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Cancelar
        </a>
    @else
        <a href="{{ route('prospectos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Cancelar
        </a>
    @endif
    
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-1"></i> 
        {{ isset($cliente) ? 'Guardar Cambios' : 'Crear Prospecto' }}
    </button>
</div>

{{-- Script para manejar visibilidad de estatus --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoClienteSelect = document.getElementById('tipo_cliente');
    const estatusField = document.getElementById('estatus-field');
    const estatusSelect = document.getElementById('estatus');
    
    if (tipoClienteSelect && estatusField) {
        function actualizarEstatus() {
            if (tipoClienteSelect.value === 'C') {
                estatusField.style.display = 'block';
                if (estatusSelect) estatusSelect.required = true;
            } else {
                estatusField.style.display = 'none';
                if (estatusSelect) {
                    estatusSelect.required = false;
                    estatusSelect.value = '';
                }
            }
        }
        
        if (tipoClienteSelect) {
            actualizarEstatus();
            tipoClienteSelect.addEventListener('change', actualizarEstatus);
        }
    }
    
    // Calcular edad automáticamente
    const fechaNacimiento = document.getElementById('fecha_nacimiento');
    if (fechaNacimiento) {
        fechaNacimiento.addEventListener('change', function() {
            if (this.value) {
                const fechaNac = new Date(this.value);
                const hoy = new Date();
                let edad = hoy.getFullYear() - fechaNac.getFullYear();
                const mes = hoy.getMonth() - fechaNac.getMonth();
                
                if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
                    edad--;
                }
                
                // Puedes asignar la edad a un campo si lo tienes
                const edadInput = document.getElementById('edad');
                if (edadInput) {
                    edadInput.value = edad;
                }
            }
        });
    }
});
</script>
@endpush