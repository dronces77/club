@csrf

@if(isset($cliente))
    @method('PUT')
@endif

<div class="row">
    <!-- Tipo de Cliente -->
    <div class="col-md-4 mb-3">
        <label for="tipo_cliente" class="form-label">Tipo de Cliente *</label>
        <select class="form-select @error('tipo_cliente') is-invalid @enderror" 
                id="tipo_cliente" 
                name="tipo_cliente" 
                required>
            <option value="">Seleccionar...</option>
            <option value="C" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'C' ? 'selected' : '' }}>Cliente</option>
            <option value="P" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'P' ? 'selected' : '' }}>Prospecto</option>
            <option value="S" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'S' ? 'selected' : '' }}>Suspendido</option>
            <option value="B" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'B' ? 'selected' : '' }}>Baja</option>
            <option value="I" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'I' ? 'selected' : '' }}>Imposible</option>
        </select>
        @error('tipo_cliente')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Estatus -->
    <div class="col-md-4 mb-3">
        <label for="estatus" class="form-label">Estatus *</label>
        <select class="form-select @error('estatus') is-invalid @enderror" 
                id="estatus" 
                name="estatus" 
                required>
            <option value="">Seleccionar...</option>
            <option value="Activo" {{ old('estatus', $cliente->estatus ?? '') == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="pendiente" {{ old('estatus', $cliente->estatus ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="Suspendido" {{ old('estatus', $cliente->estatus ?? '') == 'Suspendido' ? 'selected' : '' }}>Suspendido</option>
            <option value="Terminado" {{ old('estatus', $cliente->estatus ?? '') == 'Terminado' ? 'selected' : '' }}>Terminado</option>
            <option value="Baja" {{ old('estatus', $cliente->estatus ?? '') == 'Baja' ? 'selected' : '' }}>Baja</option>
        </select>
        @error('estatus')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- No. Cliente (solo lectura si existe) -->
    <div class="col-md-4 mb-3">
        <label for="no_cliente" class="form-label">No. Cliente</label>
        <input type="text" 
               class="form-control" 
               id="no_cliente" 
               value="{{ $cliente->no_cliente ?? 'Generado automáticamente' }}" 
               readonly>
        <small class="text-muted">Se genera automáticamente</small>
    </div>
</div>

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
        <label for="apellido_paterno" class="form-label">Apellido Paterno *</label>
        <input type="text" 
               class="form-control @error('apellido_paterno') is-invalid @enderror" 
               id="apellido_paterno" 
               name="apellido_paterno" 
               value="{{ old('apellido_paterno', $cliente->apellido_paterno ?? '') }}" 
               required>
        @error('apellido_paterno')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Apellido Materno -->
    <div class="col-md-4 mb-3">
        <label for="apellido_materno" class="form-label">Apellido Materno *</label>
        <input type="text" 
               class="form-control @error('apellido_materno') is-invalid @enderror" 
               id="apellido_materno" 
               name="apellido_materno" 
               value="{{ old('apellido_materno', $cliente->apellido_materno ?? '') }}" 
               required>
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
</div>

<hr class="my-4">

<h5 class="mb-3">
    <i class="fas fa-building me-2"></i>Datos Institucionales
</h5>

<div class="row">
    <!-- Instituto -->
    <div class="col-md-4 mb-3">
        <label for="instituto_id" class="form-label">Institución</label>
        <select class="form-select @error('instituto_id') is-invalid @enderror" 
                id="instituto_id" 
                name="instituto_id">
            <option value="">Seleccionar...</option>
            @foreach($institutos as $instituto)
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
            @foreach($regimenes as $regimen)
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

<div class="row">
    <!-- Trámite -->
    <div class="col-md-4 mb-3">
        <label for="tramite_id" class="form-label">Trámite</label>
        <select class="form-select @error('tramite_id') is-invalid @enderror" 
                id="tramite_id" 
                name="tramite_id">
            <option value="">Seleccionar...</option>
            @foreach($tramites as $tramite)
                <option value="{{ $tramite->id }}" 
                    {{ old('tramite_id', $cliente->tramite_id ?? '') == $tramite->id ? 'selected' : '' }}>
                    {{ $tramite->nombre }}
                </option>
            @endforeach
        </select>
        @error('tramite_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Modalidad -->
    <div class="col-md-4 mb-3">
        <label for="modalidad_id" class="form-label">Modalidad</label>
        <select class="form-select @error('modalidad_id') is-invalid @enderror" 
                id="modalidad_id" 
                name="modalidad_id">
            <option value="">Seleccionar...</option>
            @foreach($modalidades as $modalidad)
                <option value="{{ $modalidad->id }}" 
                    {{ old('modalidad_id', $cliente->modalidad_id ?? '') == $modalidad->id ? 'selected' : '' }}>
                    {{ $modalidad->nombre }}
                </option>
            @endforeach
        </select>
        @error('modalidad_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr class="my-4">

<!-- Botones -->
<div class="d-flex justify-content-between">
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Cancelar
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-1"></i> 
        {{ isset($cliente) ? 'Actualizar Cliente' : 'Crear Cliente' }}
    </button>
</div>
