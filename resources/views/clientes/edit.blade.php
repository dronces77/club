@extends('layouts.app')

@section('title', 'Editar Cliente - ClubPension')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>Editar Cliente: {{ $cliente->nombre }} {{ $cliente->apellido_paterno }} {{ $cliente->apellido_materno }}
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

@if($cliente->tipo_cliente !== 'C')
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Advertencia:</strong> Solo los clientes tipo "Cliente" pueden ser editados completamente. 
    Este cliente es de tipo "{{ $cliente->tipo_cliente_completo ?? $cliente->tipo_cliente }}".
</div>
@endif

<form id="editarClienteForm" action="{{ route('clientes.update', $cliente) }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- Sección 1: Información Actual -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i> Información actual:
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label"><strong>No. Cliente:</strong></label>
                    <p class="form-control-plaintext">{{ $cliente->no_cliente ?? 'N/A' }}</p>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label"><strong>Cliente desde:</strong></label>
                    <p class="form-control-plaintext">
                        {{ $cliente->fecha_contrato ? $cliente->fecha_contrato->format('d/m/Y') : 'N/A' }}
                    </p>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label"><strong>Fecha Captura:</strong></label>
                    <p class="form-control-plaintext">
                        {{ $cliente->created_at ? $cliente->created_at->format('d/m/Y H:i') : 'N/A' }}
                    </p>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label"><strong>Última actualización:</strong></label>
                    <p class="form-control-plaintext">
                        {{ $cliente->updated_at ? $cliente->updated_at->format('d/m/Y H:i') : 'N/A' }}
                    </p>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label"><strong>Estatus actual:</strong></label>
                    <p class="form-control-plaintext">
                        @php
                            $badgeClass = 'badge-secondary';
                            if($cliente->estatus == 'Activo') $badgeClass = 'badge-success';
                            elseif($cliente->estatus == 'Suspendido') $badgeClass = 'badge-warning';
                            elseif($cliente->estatus == 'Terminado') $badgeClass = 'badge-info';
                            elseif($cliente->estatus == 'Baja') $badgeClass = 'badge-danger';
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $cliente->estatus }}</span>
                    </p>
                </div>
            </div>
            
            <!-- Datos que se pueden editar -->
            <h6 class="mt-3 mb-3 text-primary">Datos que se pueden editar (los que llevan * son obligatorios):</h6>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="tipo_cliente" class="form-label">
                        Tipo de Cliente <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('tipo_cliente') is-invalid @enderror" 
                            id="tipo_cliente" 
                            name="tipo_cliente" 
                            required
                            {{ $cliente->tipo_cliente !== 'C' ? 'disabled' : '' }}>
                        <option value="">Seleccionar...</option>
                        <option value="C" {{ old('tipo_cliente', $cliente->tipo_cliente) == 'C' ? 'selected' : '' }}>Cliente</option>
                        <option value="P" {{ old('tipo_cliente', $cliente->tipo_cliente) == 'P' ? 'selected' : '' }}>Prospecto</option>
                        <option value="S" {{ old('tipo_cliente', $cliente->tipo_cliente) == 'S' ? 'selected' : '' }}>Suspendido</option>
                        <option value="B" {{ old('tipo_cliente', $cliente->tipo_cliente) == 'B' ? 'selected' : '' }}>Baja</option>
                        <option value="I" {{ old('tipo_cliente', $cliente->tipo_cliente) == 'I' ? 'selected' : '' }}>Imposible</option>
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
                        <option value="Activo" {{ old('estatus', $cliente->estatus) == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Suspendido" {{ old('estatus', $cliente->estatus) == 'Suspendido' ? 'selected' : '' }}>Suspendido</option>
                        <option value="Terminado" {{ old('estatus', $cliente->estatus) == 'Terminado' ? 'selected' : '' }}>Terminado</option>
                        <option value="Baja" {{ old('estatus', $cliente->estatus) == 'Baja' ? 'selected' : '' }}>Baja</option>
                    </select>
                    @error('estatus')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sección 2: Datos Personales -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-user me-2"></i> Datos Personales
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 mb-3">
                    <label for="nombre" class="form-label">
                        Nombre <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre', $cliente->nombre) }}" 
                           required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="apellido_paterno" class="form-label">
                        Apellido Paterno <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('apellido_paterno') is-invalid @enderror" 
                           id="apellido_paterno" 
                           name="apellido_paterno" 
                           value="{{ old('apellido_paterno', $cliente->apellido_paterno) }}" 
                           required>
                    @error('apellido_paterno')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="apellido_materno" class="form-label">
                        Apellido Materno <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('apellido_materno') is-invalid @enderror" 
                           id="apellido_materno" 
                           name="apellido_materno" 
                           value="{{ old('apellido_materno', $cliente->apellido_materno) }}" 
                           required>
                    @error('apellido_materno')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="fecha_nacimiento" class="form-label">
                        Fecha de Nacimiento <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                           id="fecha_nacimiento" 
                           name="fecha_nacimiento" 
                           value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento ? $cliente->fecha_nacimiento->format('Y-m-d') : '') }}" 
                           required>
                    @error('fecha_nacimiento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3 mb-3">
                    <label for="curp" class="form-label">
                        CURP <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('curp') is-invalid @enderror" 
                           id="curp" 
                           name="curp" 
                           value="{{ old('curp', $curps[0] ?? '') }}" 
                           maxlength="18"
                           required
                           data-campo="curp">
                    <div class="invalid-feedback" id="curp-error"></div>
                    @error('curp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="curp2" class="form-label">
                        CURP2 (opcional)
                    </label>
                    <input type="text" 
                           class="form-control @error('curp2') is-invalid @enderror" 
                           id="curp2" 
                           name="curp2" 
                           value="{{ old('curp2', $curps[1] ?? '') }}" 
                           maxlength="18"
                           data-campo="curp2">
                    <div class="invalid-feedback" id="curp2-error"></div>
                    @error('curp2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="curp3" class="form-label">
                        CURP3 (opcional)
                    </label>
                    <input type="text" 
                           class="form-control @error('curp3') is-invalid @enderror" 
                           id="curp3" 
                           name="curp3" 
                           value="{{ old('curp3', $curps[2] ?? '') }}" 
                           maxlength="18"
                           data-campo="curp3">
                    <div class="invalid-feedback" id="curp3-error"></div>
                    @error('curp3')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3 mb-3">
                    <label for="rfc" class="form-label">
                        RFC <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('rfc') is-invalid @enderror" 
                           id="rfc" 
                           name="rfc" 
                           value="{{ old('rfc', $rfcs[0] ?? '') }}" 
                           maxlength="13"
                           required
                           data-campo="rfc">
                    <div class="invalid-feedback" id="rfc-error"></div>
                    @error('rfc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="rfc2" class="form-label">
                        RFC2 (opcional)
                    </label>
                    <input type="text" 
                           class="form-control @error('rfc2') is-invalid @enderror" 
                           id="rfc2" 
                           name="rfc2" 
                           value="{{ old('rfc2', $rfcs[1] ?? '') }}" 
                           maxlength="13"
                           data-campo="rfc2">
                    <div class="invalid-feedback" id="rfc2-error"></div>
                    @error('rfc2')
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
                           value="{{ old('fecha_contrato', $cliente->fecha_contrato ? $cliente->fecha_contrato->format('Y-m-d') : '') }}" 
                           required>
                    @error('fecha_contrato')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                            <option value="{{ $clienteRef->id }}" {{ old('cliente_referidor_id', $cliente->cliente_referidor_id) == $clienteRef->id ? 'selected' : '' }}>
                                {{ $clienteRef->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_referidor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sección 3: Datos de Contacto -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-address-book me-2"></i> Datos de Contacto
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="celular1" class="form-label">
                        Celular1 <span class="text-danger">*</span>
                    </label>
                    <input type="tel" 
                           class="form-control @error('celular1') is-invalid @enderror" 
                           id="celular1" 
                           name="celular1" 
                           value="{{ old('celular1', $contactos['celular1'] ?? '') }}" 
                           maxlength="15"
                           required>
                    @error('celular1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="celular2" class="form-label">
                        Celular2 (opcional)
                    </label>
                    <input type="tel" 
                           class="form-control @error('celular2') is-invalid @enderror" 
                           id="celular2" 
                           name="celular2" 
                           value="{{ old('celular2', $contactos['celular2'] ?? '') }}" 
                           maxlength="15">
                    @error('celular2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="tel_casa" class="form-label">
                        TelCasa (opcional)
                    </label>
                    <input type="tel" 
                           class="form-control @error('tel_casa') is-invalid @enderror" 
                           id="tel_casa" 
                           name="tel_casa" 
                           value="{{ old('tel_casa', $contactos['tel_casa'] ?? '') }}" 
                           maxlength="15">
                    @error('tel_casa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="correo1" class="form-label">
                        Correo1 (opcional)
                    </label>
                    <input type="email" 
                           class="form-control @error('correo1') is-invalid @enderror" 
                           id="correo1" 
                           name="correo1" 
                           value="{{ old('correo1', $contactos['correo1'] ?? '') }}">
                    @error('correo1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="correo2" class="form-label">
                        Correo2 (opcional)
                    </label>
                    <input type="email" 
                           class="form-control @error('correo2') is-invalid @enderror" 
                           id="correo2" 
                           name="correo2" 
                           value="{{ old('correo2', $contactos['correo2'] ?? '') }}">
                    @error('correo2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="correo_personal" class="form-label">
                        CorreoPersonal (opcional)
                    </label>
                    <input type="email" 
                           class="form-control @error('correo_personal') is-invalid @enderror" 
                           id="correo_personal" 
                           name="correo_personal" 
                           value="{{ old('correo_personal', $contactos['correo_personal'] ?? '') }}">
                    @error('correo_personal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sección 4: Datos Aseguramiento -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-shield-alt me-2"></i> Datos Aseguramiento
            </h5>
        </div>
        <div class="card-body">
            <!-- Institución Principal -->
            <h6 class="mb-3">Institución Principal</h6>
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <label for="instituto_id" class="form-label">
                        Institución <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('instituto_id') is-invalid @enderror" 
                            id="instituto_id" 
                            name="instituto_id" 
                            required>
                        <option value="">Seleccionar...</option>
                        @foreach($institutos ?? [] as $instituto)
                            <option value="{{ $instituto->id }}" {{ old('instituto_id', $cliente->instituto_id) == $instituto->id ? 'selected' : '' }}>
                                {{ $instituto->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('instituto_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="regimen_id" class="form-label">
                        Régimen <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('regimen_id') is-invalid @enderror" 
                            id="regimen_id" 
                            name="regimen_id" 
                            required>
                        <option value="">Seleccionar...</option>
                        @foreach($regimenes ?? [] as $regimen)
                            <option value="{{ $regimen->id }}" {{ old('regimen_id', $cliente->regimen_id) == $regimen->id ? 'selected' : '' }}>
                                {{ $regimen->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('regimen_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="semanas_imss" class="form-label">
                        Semanas IMSS
                    </label>
                    <input type="number" 
                           class="form-control @error('semanas_imss') is-invalid @enderror" 
                           id="semanas_imss" 
                           name="semanas_imss" 
                           value="{{ old('semanas_imss', $cliente->semanas_imss) }}"
                           min="0">
                    @error('semanas_imss')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="tramite_id" class="form-label">
                        Trámite <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('tramite_id') is-invalid @enderror" 
                            id="tramite_id" 
                            name="tramite_id" 
                            required>
                        <option value="">Seleccionar...</option>
                        @foreach($tramites ?? [] as $tramite)
                            <option value="{{ $tramite->id }}" {{ old('tramite_id', $cliente->tramite_id) == $tramite->id ? 'selected' : '' }}>
                                {{ $tramite->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('tramite_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <label for="modalidad_id" class="form-label">
                        Modalidad <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('modalidad_id') is-invalid @enderror" 
                            id="modalidad_id" 
                            name="modalidad_id" 
                            required>
                        <option value="">Seleccionar...</option>
                        @foreach($modalidadesImss ?? [] as $modalidad)
                            <option value="{{ $modalidad->id }}" {{ old('modalidad_id', $cliente->modalidad_id) == $modalidad->id ? 'selected' : '' }}>
                                {{ $modalidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('modalidad_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="fecha_alta" class="form-label">
                        FechaAlta
                    </label>
                    <input type="date" 
                           class="form-control @error('fecha_alta') is-invalid @enderror" 
                           id="fecha_alta" 
                           name="fecha_alta" 
                           value="{{ old('fecha_alta', $cliente->fecha_alta ? $cliente->fecha_alta->format('Y-m-d') : '') }}">
                    @error('fecha_alta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="fecha_baja" class="form-label">
                        FechaBaja
                    </label>
                    <input type="date" 
                           class="form-control @error('fecha_baja') is-invalid @enderror" 
                           id="fecha_baja" 
                           name="fecha_baja" 
                           value="{{ old('fecha_baja', $cliente->fecha_baja ? $cliente->fecha_baja->format('Y-m-d') : '') }}">
                    @error('fecha_baja')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- NSS Múltiples -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <label for="nss" class="form-label">
                        NSS <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('nss') is-invalid @enderror" 
                           id="nss" 
                           name="nss" 
                           value="{{ old('nss', $nss[0] ?? '') }}" 
                           maxlength="11"
                           required
                           data-campo="nss">
                    <div class="invalid-feedback" id="nss-error"></div>
                    @error('nss')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="nss2" class="form-label">
                        NSS2 (opcional)
                    </label>
                    <input type="text" 
                           class="form-control @error('nss2') is-invalid @enderror" 
                           id="nss2" 
                           name="nss2" 
                           value="{{ old('nss2', $nss[1] ?? '') }}" 
                           maxlength="11"
                           data-campo="nss2">
                    <div class="invalid-feedback" id="nss2-error"></div>
                    @error('nss2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="nss3" class="form-label">
                        NSS3 (opcional)
                    </label>
                    <input type="text" 
                           class="form-control @error('nss3') is-invalid @enderror" 
                           id="nss3" 
                           name="nss3" 
                           value="{{ old('nss3', $nss[2] ?? '') }}" 
                           maxlength="11"
                           data-campo="nss3">
                    <div class="invalid-feedback" id="nss3-error"></div>
                    @error('nss3')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="nss4" class="form-label">
                        NSS4 (opcional)
                    </label>
                    <input type="text" 
                           class="form-control @error('nss4') is-invalid @enderror" 
                           id="nss4" 
                           name="nss4" 
                           value="{{ old('nss4', $nss[3] ?? '') }}" 
                           maxlength="11"
                           data-campo="nss4">
                    <div class="invalid-feedback" id="nss4-error"></div>
                    @error('nss4')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Sección 5: Institución 2 (ISSSTE) -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-building me-2"></i> Institución 2 (ISSSTE) - Opcional
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4 mb-3">
                    <label for="instituto2_id" class="form-label">
                        Institución2 (opcional)
                    </label>
                    <select class="form-select @error('instituto2_id') is-invalid @enderror" 
                            id="instituto2_id" 
                            name="instituto2_id">
                        <option value="">Seleccionar...</option>
                        <option value="">N/A</option>
                        @foreach($institutos ?? [] as $instituto)
                            @if($instituto->codigo == 'ISSSTE')
                                <option value="{{ $instituto->id }}" 
                                        {{ old('instituto2_id', $cliente->instituto2_id) == $instituto->id ? 'selected' : '' }}>
                                    {{ $instituto->nombre }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('instituto2_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="regimen2_id" class="form-label">
                        Régimen2 <span class="text-danger issste-required" style="display: none;">*</span>
                    </label>
                    <select class="form-select @error('regimen2_id') is-invalid @enderror" 
                            id="regimen2_id" 
                            name="regimen2_id"
                            disabled>
                        <option value="">Seleccionar...</option>
                        @foreach($regimenes ?? [] as $regimen)
                            @if($regimen->instituto_id == 14) <!-- ISSSTE id = 14 -->
                                <option value="{{ $regimen->id }}" {{ old('regimen2_id', $cliente->regimen2_id) == $regimen->id ? 'selected' : '' }}>
                                    {{ $regimen->nombre }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('regimen2_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="anios_servicio_issste" class="form-label">
                        Años de Servicio
                    </label>
                    <input type="number" 
                           class="form-control @error('anios_servicio_issste') is-invalid @enderror" 
                           id="anios_servicio_issste" 
                           name="anios_servicio_issste" 
                           value="{{ old('anios_servicio_issste', $cliente->anios_servicio_issste ?? $cliente->semanas_issste ?? '') }}"
                           min="0"
                           disabled>
                    @error('anios_servicio_issste')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4 mb-3">
                    <label for="tramite2_id" class="form-label">
                        Trámite2 <span class="text-danger issste-required" style="display: none;">*</span>
                    </label>
                    <select class="form-select @error('tramite2_id') is-invalid @enderror" 
                            id="tramite2_id" 
                            name="tramite2_id"
                            disabled>
                        <option value="">Seleccionar...</option>
                        @foreach($tramites ?? [] as $tramite)
                            <option value="{{ $tramite->id }}" {{ old('tramite2_id', $cliente->tramite2_id) == $tramite->id ? 'selected' : '' }}>
                                {{ $tramite->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('tramite2_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="modalidad_issste" class="form-label">
                        Modalidad2 <span class="text-danger issste-required" style="display: none;">*</span>
                    </label>
                    <select class="form-select @error('modalidad_issste') is-invalid @enderror" 
                            id="modalidad_issste" 
                            name="modalidad_issste"
                            disabled>
                        <option value="">Seleccionar...</option>
                        @foreach($modalidadesIssste ?? [] as $modalidad)
                            <option value="{{ $modalidad->codigo }}" {{ old('modalidad_issste', $cliente->modalidad_issste) == $modalidad->codigo ? 'selected' : '' }}>
                                {{ $modalidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('modalidad_issste')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="nss_issste" class="form-label">
                        NSSIssste
                    </label>
                    <input type="text" 
                           class="form-control @error('nss_issste') is-invalid @enderror" 
                           id="nss_issste" 
                           name="nss_issste" 
                           value="{{ old('nss_issste', $cliente->nss_issste) }}"
                           maxlength="11"
                           disabled>
                    @error('nss_issste')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="fecha_alta_issste" class="form-label">
                        FechaAlta_ModIssste
                    </label>
                    <input type="date" 
                           class="form-control @error('fecha_alta_issste') is-invalid @enderror" 
                           id="fecha_alta_issste" 
                           name="fecha_alta_issste" 
                           value="{{ old('fecha_alta_issste', $cliente->fecha_alta_issste ? $cliente->fecha_alta_issste->format('Y-m-d') : '') }}"
                           disabled>
                    @error('fecha_alta_issste')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="fecha_baja_issste" class="form-label">
                        FechaBaja_ModIssste
                    </label>
                    <input type="date" 
                           class="form-control @error('fecha_baja_issste') is-invalid @enderror" 
                           id="fecha_baja_issste" 
                           name="fecha_baja_issste" 
                           value="{{ old('fecha_baja_issste', $cliente->fecha_baja_issste ? $cliente->fecha_baja_issste->format('Y-m-d') : '') }}"
                           disabled>
                    @error('fecha_baja_issste')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Instrucciones:</strong> 
                <ul class="mb-0 mt-2">
                    <li>Seleccione <strong>"ISSSTE"</strong> para habilitar los campos de Institución 2</li>
                    <li>Seleccione <strong>"N/A"</strong> si no tiene segunda institución</li>
                    <li>Si selecciona ISSSTE, los campos <strong>Régimen2, Trámite2 y Modalidad2</strong> son obligatorios</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Sección 6: Datos Económicos -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-money-bill-wave me-2"></i> Datos Económicos
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="pension_default" class="form-label">
                        PensionDefault <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" 
                               step="0.01" 
                               class="form-control @error('pension_default') is-invalid @enderror" 
                               id="pension_default" 
                               name="pension_default"
                               value="{{ old('pension_default', $cliente->pension_default) }}"
                               required>
                    </div>
                    @error('pension_default')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="pension_normal" class="form-label">
                        PensionNormal <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" 
                               step="0.01" 
                               class="form-control @error('pension_normal') is-invalid @enderror" 
                               id="pension_normal" 
                               name="pension_normal"
                               value="{{ old('pension_normal', $cliente->pension_normal) }}"
                               required>
                    </div>
                    @error('pension_normal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="comision" class="form-label">
                        Comisión <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" 
                               step="0.01" 
                               class="form-control @error('comision') is-invalid @enderror" 
                               id="comision" 
                               name="comision"
                               value="{{ old('comision', $cliente->comision) }}"
                               required>
                    </div>
                    @error('comision')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="honorarios" class="form-label">
                        Honorarios <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" 
                               step="0.01" 
                               class="form-control @error('honorarios') is-invalid @enderror" 
                               id="honorarios" 
                               name="honorarios"
                               value="{{ old('honorarios', $cliente->honorarios) }}"
                               required>
                    </div>
                    @error('honorarios')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary" {{ $cliente->tipo_cliente !== 'C' ? 'disabled' : '' }}>
                    <i class="fas fa-save me-1"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar régimenes según institución seleccionada
    const institutoSelect = document.getElementById('instituto_id');
    const regimenSelect = document.getElementById('regimen_id');
    const modalidadSelect = document.getElementById('modalidad_id');
    
    const regimenesData = @json($regimenes ?? []);
    const modalidadesImssData = @json($modalidadesImss ?? []);
    const modalidadesIsssteData = @json($modalidadesIssste ?? []);
    
    // Función para cargar regímenes - CORREGIDA
    function cargarRegimenes(institutoId) {
        // Guardar el valor actualmente seleccionado
        const valorActual = regimenSelect.value;
        
        // Limpiar opciones excepto la primera
        while (regimenSelect.options.length > 1) {
            regimenSelect.remove(1);
        }
        
        if (institutoId) {
            const regimenesFiltrados = regimenesData.filter(r => r.instituto_id == institutoId);
            
            regimenesFiltrados.forEach(regimen => {
                const option = document.createElement('option');
                option.value = regimen.id;
                option.textContent = regimen.nombre;
                
                // Seleccionar si coincide con el valor actual
                if (regimen.id == valorActual) {
                    option.selected = true;
                }
                
                regimenSelect.appendChild(option);
            });
            
            regimenSelect.disabled = false;
            
            // Si el valor actual no está en las opciones filtradas y hay un valor en el modelo, seleccionarlo
            if (!valorActual && {{ $cliente->regimen_id ?? 'null' }}) {
                const regimenIdCliente = {{ $cliente->regimen_id ?? 'null' }};
                const regimenEncontrado = regimenesFiltrados.find(r => r.id == regimenIdCliente);
                if (regimenEncontrado) {
                    regimenSelect.value = regimenIdCliente;
                }
            }
        } else {
            regimenSelect.disabled = true;
        }
    }
    
    // Función para cargar modalidades según institución - CORREGIDA
    function cargarModalidades(institutoId) {
        // Guardar el valor actualmente seleccionado
        const valorActual = modalidadSelect.value;
        
        // Limpiar opciones excepto la primera
        while (modalidadSelect.options.length > 1) {
            modalidadSelect.remove(1);
        }
        
        let modalidadesData;
        
        if (institutoId == 13) { // IMSS id = 13
            modalidadesData = modalidadesImssData;
        } else if (institutoId == 14) { // ISSSTE id = 14
            modalidadesData = modalidadesIsssteData;
        } else {
            modalidadesData = [...modalidadesImssData, ...modalidadesIsssteData];
        }
        
        modalidadesData.forEach(modalidad => {
            const option = document.createElement('option');
            option.value = modalidad.id;
            option.textContent = modalidad.nombre;
            
            // Seleccionar si coincide con el valor actual
            if (modalidad.id == valorActual) {
                option.selected = true;
            }
            
            modalidadSelect.appendChild(option);
        });
        
        modalidadSelect.disabled = false;
        
        // Si el valor actual no está en las opciones filtradas y hay un valor en el modelo, seleccionarlo
        if (!valorActual && {{ $cliente->modalidad_id ?? 'null' }}) {
            const modalidadIdCliente = {{ $cliente->modalidad_id ?? 'null' }};
            const modalidadEncontrada = modalidadesData.find(m => m.id == modalidadIdCliente);
            if (modalidadEncontrada) {
                modalidadSelect.value = modalidadIdCliente;
            }
        }
    }
    
    // Eventos
    if (institutoSelect && regimenSelect) {
        // Cargar al inicio CON LOS VALORES DEL MODELO
        cargarRegimenes(institutoSelect.value);
        cargarModalidades(institutoSelect.value);
        
        // Asegurarse de que los valores del modelo se mantengan después de cargar
        setTimeout(() => {
            // Régimen
            const regimenIdCliente = {{ $cliente->regimen_id ?? 'null' }};
            if (regimenIdCliente && regimenSelect.value !== regimenIdCliente) {
                regimenSelect.value = regimenIdCliente;
            }
            
            // Modalidad
            const modalidadIdCliente = {{ $cliente->modalidad_id ?? 'null' }};
            if (modalidadIdCliente && modalidadSelect.value !== modalidadIdCliente) {
                modalidadSelect.value = modalidadIdCliente;
            }
        }, 100);
        
        // Actualizar al cambiar institución
        institutoSelect.addEventListener('change', function() {
            cargarRegimenes(this.value);
            cargarModalidades(this.value);
        });
    }
    
    // Validación de CURP
    const curpInput = document.getElementById('curp');
    if (curpInput) {
        curpInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Validación de RFC
    const rfcInput = document.getElementById('rfc');
    if (rfcInput) {
        rfcInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
    
    // ==================== CONTROL ISSSTE ====================
    const instituto2Select = document.getElementById('instituto2_id');
    
    // Campos ISSSTE que se deben habilitar/deshabilitar
    const camposIssste = [
        'regimen2_id',
        'anios_servicio_issste', 
        'tramite2_id',
        'modalidad_issste',
        'nss_issste',
        'fecha_alta_issste',
        'fecha_baja_issste'
    ];
    
    // Campos obligatorios cuando ISSSTE está seleccionado
    const camposObligatorios = ['regimen2_id', 'tramite2_id', 'modalidad_issste'];
    
    // Función para habilitar/deshabilitar campos ISSSTE
    function toggleCamposIssste(habilitar) {
        const esIsssteSeleccionado = habilitar;
        
        camposIssste.forEach(id => {
            const campo = document.getElementById(id);
            if (campo) {
                // Habilitar/deshabilitar campo
                campo.disabled = !habilitar;
                
                // Marcar como requerido solo si es ISSSTE y está en la lista de obligatorios
                if (camposObligatorios.includes(id)) {
                    campo.required = habilitar;
                }
                
                // Mostrar/ocultar asterisco rojo
                const label = document.querySelector(`label[for="${id}"]`);
                if (label) {
                    const asterisco = label.querySelector('.issste-required');
                    if (asterisco) {
                        asterisco.style.display = habilitar ? 'inline' : 'none';
                    }
                }
            }
        });
    }
    
    // Función para verificar si ISSSTE está seleccionado
    function esIsssteSeleccionado() {
        if (!instituto2Select) return false;
        
        const valorSeleccionado = instituto2Select.value;
        const textoSeleccionado = instituto2Select.options[instituto2Select.selectedIndex].text;
        
        // Verificar si el valor es 14 (ID de ISSSTE según schema nuevo) o el texto es "ISSSTE"
        return valorSeleccionado == 14 || textoSeleccionado === 'ISSSTE';
    }
    
    // Configurar estado inicial
    if (instituto2Select) {
        // Estado inicial basado en valor actual
        toggleCamposIssste(esIsssteSeleccionado());
        
        // Agregar evento change
        instituto2Select.addEventListener('change', function() {
            toggleCamposIssste(esIsssteSeleccionado());
        });
        
        // También agregar evento para cuando se carga la página y hay un valor
        window.addEventListener('load', function() {
            toggleCamposIssste(esIsssteSeleccionado());
        });
    }
    
    // ==================== CONTROL MODALIDADES SEGÚN INSTITUCIÓN ====================
    if (institutoSelect && modalidadSelect) {
        // Función para actualizar modalidades según institución - CORREGIDA
        function actualizarModalidades() {
            const institutoId = institutoSelect.value;
            const todasModalidades = @json($modalidadesImss ?? []);
            const modalidadesIssste = @json($modalidadesIssste ?? []);
            
            // Guardar valor actual
            const valorActual = modalidadSelect.value;
            
            // Limpiar opciones excepto la primera
            while (modalidadSelect.options.length > 1) {
                modalidadSelect.remove(1);
            }
            
            let modalidadesFiltradas = [];
            
            if (institutoId == 13) { // IMSS id = 13
                modalidadesFiltradas = todasModalidades.filter(m => 
                    ['NA', 'M10', 'M40'].includes(m.codigo)
                );
            } else if (institutoId == 14) { // ISSSTE id = 14
                modalidadesFiltradas = modalidadesIssste.filter(m => 
                    ['NA', 'CV'].includes(m.codigo)
                );
            } else {
                // Si no hay institución, mostrar todas
                modalidadesFiltradas = [...todasModalidades, ...modalidadesIssste];
            }
            
            // Agregar opciones filtradas
            modalidadesFiltradas.forEach(modalidad => {
                const option = document.createElement('option');
                option.value = modalidad.id;
                option.textContent = modalidad.nombre;
                
                // Seleccionar si es el valor actual
                if (modalidad.id == valorActual) {
                    option.selected = true;
                }
                
                modalidadSelect.appendChild(option);
            });
            
            // Si el valor actual no está en las opciones filtradas, verificar si hay valor del modelo
            if (valorActual && !modalidadesFiltradas.some(m => m.id == valorActual)) {
                const modalidadIdCliente = {{ $cliente->modalidad_id ?? 'null' }};
                const modalidadEncontrada = modalidadesFiltradas.find(m => m.id == modalidadIdCliente);
                if (modalidadEncontrada) {
                    modalidadSelect.value = modalidadIdCliente;
                } else {
                    modalidadSelect.value = '';
                }
            }
        }
        
        // Configurar estado inicial
        actualizarModalidades();
        
        // Asegurarse de mantener el valor del modelo
        setTimeout(() => {
            const modalidadIdCliente = {{ $cliente->modalidad_id ?? 'null' }};
            if (modalidadIdCliente && modalidadSelect.value !== modalidadIdCliente) {
                modalidadSelect.value = modalidadIdCliente;
            }
        }, 100);
        
        // Agregar evento change
        institutoSelect.addEventListener('change', actualizarModalidades);
    }
    
    // ==================== CONTROL RÉGIMENES SEGÚN INSTITUCIÓN ====================
    if (institutoSelect && regimenSelect) {
        function actualizarRegimenes() {
            const institutoId = institutoSelect.value;
            const valorActual = regimenSelect.value;
            
            // Limpiar opciones excepto la primera
            while (regimenSelect.options.length > 1) {
                regimenSelect.remove(1);
            }
            
            if (institutoId) {
                const regimenesFiltrados = regimenesData.filter(r => r.instituto_id == institutoId);
                
                regimenesFiltrados.forEach(regimen => {
                    const option = document.createElement('option');
                    option.value = regimen.id;
                    option.textContent = regimen.nombre;
                    
                    if (regimen.id == valorActual) {
                        option.selected = true;
                    }
                    
                    regimenSelect.appendChild(option);
                });
                
                regimenSelect.disabled = false;
            } else {
                regimenSelect.disabled = true;
            }
        }
        
        actualizarRegimenes();
        
        // Asegurarse de mantener el valor del modelo
        setTimeout(() => {
            const regimenIdCliente = {{ $cliente->regimen_id ?? 'null' }};
            if (regimenIdCliente && regimenSelect.value !== regimenIdCliente) {
                regimenSelect.value = regimenIdCliente;
            }
        }, 100);
        
        institutoSelect.addEventListener('change', actualizarRegimenes);
    }
    
    // ==================== VALIDACIÓN EN TIEMPO REAL ====================
    // Obtener ID del cliente actual
    const clienteId = {{ $cliente->id }};
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Función para validar un campo único
    function validarCampoUnico(campo, valor) {
        if (!valor) return Promise.resolve(true);
        
        return fetch('/api/validar-campo-unico', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                campo: campo,
                valor: valor,
                cliente_id: clienteId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la validación');
            }
            return response.json();
        })
        .then(data => {
            return {
                disponible: data.disponible,
                mensaje: data.mensaje
            };
        })
        .catch(error => {
            console.error('Error en validación:', error);
            return { disponible: true, mensaje: '' };
        });
    }
    
    // Configurar validación para todos los campos CURP
    const camposCurp = ['curp', 'curp2', 'curp3'];
    camposCurp.forEach(campoId => {
        const campo = document.getElementById(campoId);
        if (campo) {
            campo.addEventListener('blur', async function() {
                const valor = this.value.trim().toUpperCase();
                this.value = valor;
                
                if (valor) {
                    const resultado = await validarCampoUnico(campoId, valor);
                    if (!resultado.disponible) {
                        alert(resultado.mensaje);
                        this.classList.add('is-invalid');
                        const errorElement = document.getElementById(`${campoId}-error`);
                        if (errorElement) {
                            errorElement.textContent = resultado.mensaje;
                        }
                        this.focus();
                    } else {
                        this.classList.remove('is-invalid');
                        const errorElement = document.getElementById(`${campoId}-error`);
                        if (errorElement) {
                            errorElement.textContent = '';
                        }
                    }
                }
            });
        }
    });
    
    // Configurar validación para todos los campos RFC
    const camposRfc = ['rfc', 'rfc2'];
    camposRfc.forEach(campoId => {
        const campo = document.getElementById(campoId);
        if (campo) {
            campo.addEventListener('blur', async function() {
                const valor = this.value.trim().toUpperCase();
                this.value = valor;
                
                if (valor) {
                    const resultado = await validarCampoUnico(campoId, valor);
                    if (!resultado.disponible) {
                        alert(resultado.mensaje);
                        this.classList.add('is-invalid');
                        const errorElement = document.getElementById(`${campoId}-error`);
                        if (errorElement) {
                            errorElement.textContent = resultado.mensaje;
                        }
                        this.focus();
                    } else {
                        this.classList.remove('is-invalid');
                        const errorElement = document.getElementById(`${campoId}-error`);
                        if (errorElement) {
                            errorElement.textContent = '';
                        }
                    }
                }
            });
        }
    });
    
    // Configurar validación para todos los campos NSS
    const camposNss = ['nss', 'nss2', 'nss3', 'nss4'];
    camposNss.forEach(campoId => {
        const campo = document.getElementById(campoId);
        if (campo) {
            campo.addEventListener('blur', async function() {
                const valor = this.value.trim();
                
                if (valor) {
                    const resultado = await validarCampoUnico(campoId, valor);
                    if (!resultado.disponible) {
                        alert(resultado.mensaje);
                        this.classList.add('is-invalid');
                        const errorElement = document.getElementById(`${campoId}-error`);
                        if (errorElement) {
                            errorElement.textContent = resultado.mensaje;
                        }
                        this.focus();
                    } else {
                        this.classList.remove('is-invalid');
                        const errorElement = document.getElementById(`${campoId}-error`);
                        if (errorElement) {
                            errorElement.textContent = '';
                        }
                    }
                }
            });
        }
    });
    
    // Validación también en submit para evitar envío con datos duplicados
    const form = document.getElementById('editarClienteForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            let tieneErrores = false;
            const errores = [];
            
            // Validar campos CURP
            for (const campoId of camposCurp) {
                const campo = document.getElementById(campoId);
                if (campo && campo.value.trim()) {
                    const resultado = await validarCampoUnico(campoId, campo.value.trim().toUpperCase());
                    if (!resultado.disponible) {
                        tieneErrores = true;
                        campo.classList.add('is-invalid');
                        const errorElement = document.getElementById(`${campoId}-error`);
                        if (errorElement) {
                            errorElement.textContent = resultado.mensaje;
                        }
                        errores.push(resultado.mensaje);
                    }
                }
            }
            
            // Validar campos RFC
            for (const campoId of camposRfc) {
                const campo = document.getElementById(campoId);
                if (campo && campo.value.trim()) {
                    const resultado = await validarCampoUnico(campoId, campo.value.trim().toUpperCase());
                    if (!resultado.disponible) {
                        tieneErrores = true;
                        campo.classList.add('is-invalid');
                        const errorElement = document.getElementById(`${campoId}-error`);
                        if (errorElement) {
                            errorElement.textContent = resultado.mensaje;
                        }
                        errores.push(resultado.mensaje);
                    }
                }
            }
            
            // Validar campos NSS
            for (const campoId of camposNss) {
                const campo = document.getElementById(campoId);
                if (campo && campo.value.trim()) {
                    const resultado = await validarCampoUnico(campoId, campo.value.trim());
                    if (!resultado.disponible) {
                        tieneErrores = true;
                        campo.classList.add('is-invalid');
                        const errorElement = document.getElementById(`${campoId}-error`);
                        if (errorElement) {
                            errorElement.textContent = resultado.mensaje;
                        }
                        errores.push(resultado.mensaje);
                    }
                }
            }
            
            if (tieneErrores) {
                const mensajeErrores = errores.join('\n');
                alert('❌ Error: Se encontraron los siguientes problemas:\n\n' + mensajeErrores + 
                      '\n\nPor favor, corrige estos campos antes de continuar.');
                return false;
            }
            
            // Si no hay errores, mostrar confirmación
            if (!confirm('¿Estás seguro de guardar los cambios?')) {
                return false;
            }
            
            // Si todo está bien, enviar el formulario
            this.submit();
        });
    }
    
    // Función para convertir a mayúsculas automáticamente
    document.querySelectorAll('input[type="text"]').forEach(input => {
        input.addEventListener('input', function() {
            if (this.id.includes('curp') || this.id.includes('rfc')) {
                this.value = this.value.toUpperCase();
            }
        });
    });
});
</script>

<style>
    .issste-required {
        color: #dc3545;
        font-weight: bold;
    }
    
    .campos-issste-disabled {
        background-color: #f8f9fa;
        opacity: 0.7;
    }
    
    .campos-issste-enabled {
        background-color: #fff;
        opacity: 1;
    }
    
    .is-invalid {
        border-color: #dc3545;
    }
    
    .invalid-feedback {
        display: block;
    }
</style>
@endpush
@endsection
