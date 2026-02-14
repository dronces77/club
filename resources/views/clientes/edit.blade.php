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
				<div class="col-md-2 mb-2">
					<label class="form-label"><strong>No. Cliente:</strong></label>
					<p class="form-control-plaintext">{{ $cliente->no_cliente ?? 'N/A' }}</p>
				</div>
				
				<div class="col-md-2 mb-2">
					<label class="form-label"><strong>Estatus:</strong></label>
					<p class="form-control-plaintext">
						{{ $cliente->estatusCliente->nombre ?? 'N/A' }}
					</p>
				</div>
				
				<div class="col-md-2 mb-2">
					<label class="form-label"><strong>Tipo de Cliente:</strong></label>
					<p class="form-control-plaintext">
						{{ $cliente->tipo_cliente == 'C' ? 'Cliente' : '' }}
					</p>
				</div>
				
				<div class="col-md-2 mb-2">
					<label class="form-label"><strong>Cliente desde:</strong></label>
					<p class="form-control-plaintext">
						{{ $cliente->fecha_contrato ? $cliente->fecha_contrato->format('d/m/Y') : 'N/A' }}
					</p>
				</div>
				
				<div class="col-md-2 mb-2">
					<label class="form-label"><strong>Fecha Captura:</strong></label>
					<p class="form-control-plaintext">
						{{ $cliente->created_at ? $cliente->created_at->format('d/m/Y H:i') : 'N/A' }}
					</p>
				</div>
				
				<div class="col-md-2 mb-2">
					<label class="form-label"><strong>Última actualización:</strong></label>
					<p class="form-control-plaintext">
						{{ $cliente->updated_at ? $cliente->updated_at->format('d/m/Y H:i') : 'N/A' }}
					</p>
				</div>
			</div>
				
            <!-- Datos que se pueden editar -->
            <h6 class="mt-3 mb-3 text-primary">Datos que se pueden editar (los que llevan * son obligatorios):</h6>
            
            <div class="row">
				<!-- Estatus: cargado desde catalogo_estatus_clientes -->
				<div class="col-md-2 mb-3">
					<label for="estatus_cliente_id" class="form-label">
						Estatus actual <span class="text-danger">*</span>
					</label>
					<select class="form-select @error('estatus_cliente_id') is-invalid @enderror" 
							id="estatus_cliente_id" 
							name="estatus_cliente_id" 
							required>
						<option value="">Seleccionar...</option>
						@foreach($estatuses as $estatus)
							<option value="{{ $estatus->id }}" 
								{{ old('estatus_cliente_id', $cliente->estatus_cliente_id) == $estatus->id ? 'selected' : '' }}>
								{{ $estatus->nombre }}
							</option>
						@endforeach
					</select>
					@error('estatus_cliente_id')
						<div class="invalid-feedback">{{ $message }}</div>
					@enderror
				</div>
            </div>
        </div>
    </div>
    
<!-- ==================== Sección 2: Datos Personales ==================== -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="fas fa-user me-2"></i> Datos Personales
        </h5>
    </div>
    <div class="card-body">

        <!-- Nombre y Apellidos -->
        <div class="row mb-3">
            <div class="col-md-3 mb-3">
                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                       id="nombre" name="nombre"
                       value="{{ old('nombre', $cliente->nombre) }}" required>
                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="apellido_paterno" class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                       id="apellido_paterno" name="apellido_paterno"
                       value="{{ old('apellido_paterno', $cliente->apellido_paterno) }}" required>
                @error('apellido_paterno') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="apellido_materno" class="form-label">Apellido Materno <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                       id="apellido_materno" name="apellido_materno"
                       value="{{ old('apellido_materno', $cliente->apellido_materno) }}" required>
                @error('apellido_materno') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                       id="fecha_nacimiento" name="fecha_nacimiento"
                       value="{{ old('fecha_nacimiento', $cliente->fecha_nacimiento ? $cliente->fecha_nacimiento->format('Y-m-d') : '') }}"
                       required>
                @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>


<!-- ==================== CURP Y RFC DINÁMICOS EN LA MISMA FILA ==================== -->
<div class="row">
    <!-- COLUMNA IZQUIERDA: CURPs -->
    <div class="col-md-6">
        <h6 class="mb-2">CURPs</h6>
        <div id="curps-container">
            @if(!empty($curps))
                @foreach($curps as $i => $curpItem)
                <div class="row curp-item mb-2" id="curp-row-{{ $i }}">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="curp{{ $i }}" class="form-label">CURP *</label>
                                <input type="text" id="curp{{ $i }}" name="curps[{{ $i }}][curp]" class="form-control curp-input"
                                       required maxlength="18" pattern="[A-Z0-9]{18}" placeholder="18 caracteres"
                                       value="{{ old('curps.'.$i.'.curp', is_array($curpItem) ? $curpItem['curp'] : $curpItem) }}">
                                <div class="invalid-feedback" id="curp{{ $i }}-error"></div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check">
                                    <input type="checkbox" id="curp{{ $i }}-principal" name="curps[{{ $i }}][es_principal]" class="form-check-input" value="1"
                                        {{ old('curps.'.$i.'.es_principal', is_array($curpItem) ? ($curpItem['es_principal'] ?? false) : ($i === 0 ? 1 : 0)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="curp{{ $i }}-principal">Principal</label>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm eliminar-curp">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <button type="button" id="agregar-curp" class="btn btn-primary btn-sm mb-3">
            <i class="fas fa-plus"></i> Agregar CURP
        </button>
    </div>

    <!-- COLUMNA DERECHA: RFCs -->
    <div class="col-md-6">
        <h6 class="mb-2">RFCs</h6>
        <div id="rfcs-container">
            @if(!empty($rfcs))
                @foreach($rfcs as $i => $rfcItem)
                <div class="row rfc-item mb-2" id="rfc-row-{{ $i }}">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="rfc{{ $i }}" class="form-label">RFC <span class="text-danger">*</span></label>
                                <input type="text" id="rfc{{ $i }}" 
                                       name="rfcs[{{ $i }}][rfc]" 
                                       class="form-control rfc-input" 
                                       value="{{ old('rfcs.'.$i.'.rfc', is_array($rfcItem) ? $rfcItem['rfc'] : $rfcItem) }}" 
                                       maxlength="13" required>
                                <div class="invalid-feedback" id="rfc{{ $i }}-error"></div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           id="rfc{{ $i }}-principal" 
                                           name="rfcs[{{ $i }}][es_principal]" 
                                           class="form-check-input" 
                                           value="1"
                                           {{ old('rfcs.'.$i.'.es_principal', is_array($rfcItem) && ($rfcItem['es_principal'] ?? false)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rfc{{ $i }}-principal">Principal</label>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm eliminar-rfc">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <button type="button" class="btn btn-primary mb-3" id="agregar-rfc">
            <i class="fas fa-plus"></i> Agregar RFC
        </button>
    </div>
</div>

        <!-- Fecha Contrato y Referencia -->
        <div class="row mb-3">
            <div class="col-md-3 mb-3">
                <label for="fecha_contrato" class="form-label">Fecha Contrato <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('fecha_contrato') is-invalid @enderror"
                       id="fecha_contrato" name="fecha_contrato"
                       value="{{ old('fecha_contrato', $cliente->fecha_contrato ? $cliente->fecha_contrato->format('Y-m-d') : '') }}" required>
                @error('fecha_contrato') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="cliente_referidor_id" class="form-label">Referencia <span class="text-danger">*</span></label>
                <select class="form-select @error('cliente_referidor_id') is-invalid @enderror"
                        id="cliente_referidor_id" name="cliente_referidor_id">
                    <option value="">N/A</option>
                    @foreach($clientesReferencia ?? [] as $clienteRef)
                        <option value="{{ $clienteRef->id }}"
                            {{ old('cliente_referidor_id', $cliente->cliente_referidor_id) == $clienteRef->id ? 'selected' : '' }}>
                            {{ $clienteRef->nombre_completo }}
                        </option>
                    @endforeach
                </select>
                @error('cliente_referidor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
		
<!-- ==================== CONTACTOS DINÁMICOS ==================== -->
<h6 class="mb-3">Contactos</h6>
<div id="contactos-container">
@if(isset($contactos) && count($contactos) > 0)
    @foreach($contactos as $i => $contacto)
    <div class="row contacto-item mb-3">
        <div class="col-md-2">
            <select name="contactos[{{ $i }}][tipo_contacto_id]" class="form-select contacto-tipo" required>
                <option value="">Seleccione</option>
                @foreach($tiposContacto as $tipo)
                    <option value="{{ $tipo->id }}"
                        {{ old('contactos.'.$i.'.tipo_contacto_id', $contacto['tipo_contacto_id'] ?? '') == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="contactos[{{ $i }}][valor]" class="form-control contacto-valor" required
                value="{{ old('contactos.'.$i.'.valor', $contacto['valor'] ?? '') }}">
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="contactos[{{ $i }}][es_principal]" value="1"
                    {{ old('contactos.'.$i.'.es_principal', $contacto['es_principal'] ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">Principal</label>
            </div>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm eliminar-contacto">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
    @endforeach
@else
    <!-- Si no hay contactos, mostrar un contacto vacío -->
    <div class="row contacto-item mb-3">
        <div class="col-md-2">
            <select name="contactos[0][tipo_contacto_id]" class="form-select contacto-tipo" required>
                <option value="">Seleccione</option>
                @foreach($tiposContacto as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="contactos[0][valor]" class="form-control contacto-valor" required>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="contactos[0][es_principal]" value="1">
                <label class="form-check-label">Principal</label>
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm eliminar-contacto">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
@endif
</div>

<button type="button" id="agregar-contacto" class="btn btn-primary btn-sm mb-3">
    <i class="fas fa-plus"></i> Agregar Contacto
</button>

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
                <label for="instituto_id" class="form-label">Institución <span class="text-danger">*</span></label>
                <select class="form-select @error('instituto_id') is-invalid @enderror" 
                        id="instituto_id" name="instituto_id" required>
                    <option value="">Seleccionar...</option>
                    @foreach($institutos ?? [] as $instituto)
                        <option value="{{ $instituto->id }}" 
                            {{ old('instituto_id', $cliente->instituto_id) == $instituto->id ? 'selected' : '' }}>
                            {{ $instituto->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('instituto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="regimen_id" class="form-label">Régimen <span class="text-danger">*</span></label>
                <select class="form-select @error('regimen_id') is-invalid @enderror" 
                        id="regimen_id" name="regimen_id">
                    <option value="">Seleccionar...</option>
                    @foreach($regimenes ?? [] as $regimen)
                        <option value="{{ $regimen->id }}" 
                            {{ old('regimen_id', $cliente->regimen_id) == $regimen->id ? 'selected' : '' }}>
                            {{ $regimen->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('regimen_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="tramite_id" class="form-label">Trámite <span class="text-danger">*</span></label>
                <select class="form-select @error('tramite_id') is-invalid @enderror" 
                        id="tramite_id" name="tramite_id">
                    <option value="">Seleccionar...</option>
                    @foreach($tramites ?? [] as $tramite)
                        <option value="{{ $tramite->id }}" 
                            {{ old('tramite_id', $cliente->tramite_id) == $tramite->id ? 'selected' : '' }}>
                            {{ $tramite->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('tramite_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="modalidad_id" class="form-label">Modalidad <span class="text-danger">*</span></label>
                <select class="form-select @error('modalidad_id') is-invalid @enderror" 
                        id="modalidad_id" name="modalidad_id">
                    <option value="">Seleccionar...</option>
                    @foreach($modalidadesImss ?? [] as $modalidad)
                        <option value="{{ $modalidad->id }}" 
                            {{ old('modalidad_id', $cliente->modalidad_id) == $modalidad->id ? 'selected' : '' }}>
                            {{ $modalidad->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('modalidad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Semanas IMSS, Fecha Alta/Baja -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <label for="semanas_imss" class="form-label">Semanas IMSS</label>
                <input type="number" class="form-control @error('semanas_imss') is-invalid @enderror" 
                       id="semanas_imss" name="semanas_imss" 
                       value="{{ old('semanas_imss', $cliente->semanas_imss) }}" min="0">
                @error('semanas_imss') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="fecha_alta" class="form-label">Fecha Alta</label>
                <input type="date" class="form-control @error('fecha_alta') is-invalid @enderror" 
                       id="fecha_alta" name="fecha_alta" 
                       value="{{ old('fecha_alta', $cliente->fecha_alta ? $cliente->fecha_alta->format('Y-m-d') : '') }}">
                @error('fecha_alta') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="fecha_baja" class="form-label">Fecha Baja</label>
                <input type="date" class="form-control @error('fecha_baja') is-invalid @enderror" 
                       id="fecha_baja" name="fecha_baja" 
                       value="{{ old('fecha_baja', $cliente->fecha_baja ? $cliente->fecha_baja->format('Y-m-d') : '') }}">
                @error('fecha_baja') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

<!-- ==================== NSS DINÁMICOS ==================== -->
<h6 class="mb-3">NSS</h6>
<div id="nss-container">
@foreach($nss as $i => $nssItem)
<div class="row nss-item mb-3">
    <div class="col-md-2">
        <label for="nss{{ $i }}" class="form-label">NSS *</label>
        <input type="text" id="nss{{ $i }}" 
               name="nss[{{ $i }}][nss]" 
               class="form-control nss-input" 
               maxlength="11" pattern="\d{11}" 
               placeholder="11 dígitos"
               value="{{ old('nss.'.$i.'.nss', is_array($nssItem) ? $nssItem['nss'] : $nssItem) }}">
        <div class="invalid-feedback" id="nss{{ $i }}-error"></div>
    </div>
    <div class="col-md-1 d-flex align-items-end">
        <div class="form-check">
            <input type="checkbox" 
                   id="nss{{ $i }}-principal" 
                   name="nss[{{ $i }}][es_principal]" 
                   class="form-check-input" 
                   value="1"
                   {{ old('nss.'.$i.'.es_principal', is_array($nssItem) && ($nssItem['es_principal'] ?? false)) ? 'checked' : '' }}>
            <label class="form-check-label" for="nss{{ $i }}-principal">Principal</label>
        </div>
    </div>
    <div class="col-md-1 d-flex align-items-end">
        <button type="button" class="btn btn-danger btn-sm eliminar-nss">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</div>
@endforeach
</div>

<button type="button" id="agregar-nss" class="btn btn-primary btn-sm mb-3">
    <i class="fas fa-plus"></i> Agregar NSS
</button>



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
            <div class="col-md-3 mb-3">
                <label for="instituto2_id" class="form-label">Institución 2<span class="text-danger">*</span></label>
                <select class="form-select @error('instituto2_id') is-invalid @enderror" id="instituto2_id" name="instituto2_id" required>
                    <option value="">Seleccionar...</option>
                    @foreach($institutosISSSTE ?? [] as $instituto)
                        <option value="{{ $instituto->id }}" 
                            {{ old('instituto2_id', $cliente->instituto2_id) == $instituto->id ? 'selected' : '' }}>
                            {{ $instituto->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('instituto2_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
			
			
			
			
            <div class="col-md-3 mb-3">
                <label for="regimen2_id" class="form-label">Régimen 2<span class="text-danger">*</span></label>
                <select class="form-select @error('regimen2_id') is-invalid @enderror" id="regimen2_id" name="regimen2_id">
                    <option value="">Seleccionar...</option>
                    @foreach($regimenesISSSTE ?? [] as $regimen)
                        <option value="{{ $regimen->id }}" 
                            {{ old('regimen2_id', $cliente->regimen2_id) == $regimen->id ? 'selected' : '' }}>
                            {{ $regimen->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('regimen2_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>




            <div class="col-md-3 mb-3">
                <label for="tramite2_id" class="form-label">Trámite2*</label>
                <select class="form-select" id="tramite2_id" name="tramite2_id">
                    <option value="">Seleccionar...</option>
                    @foreach($tramitesISSSTE ?? [] as $tramite)
                        <option value="{{ $tramite->id }}" {{ old('tramite2_id', $cliente->tramite2_id) == $tramite->id ? 'selected' : '' }}>
                            {{ $tramite->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label for="modalidad2_id" class="form-label">Modalidad2*</label>
                <select class="form-select" id="modalidad2_id" name="modalidad2_id">
                    <option value="">Seleccionar...</option>
                    @foreach($modalidadesIssste ?? [] as $modalidad)
                        <option value="{{ $modalidad->id }}" {{ old('modalidad2_id', $cliente->modalidad2_id) == $modalidad->id ? 'selected' : '' }}>
                            {{ $modalidad->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 mb-3">
                <label for="anios_servicio_issste" class="form-label">Años de Servicio</label>
                <input type="number" class="form-control @error('anios_servicio_issste') is-invalid @enderror" 
                       id="anios_servicio_issste" name="anios_servicio_issste" 
                       value="{{ old('anios_servicio_issste', $cliente->anios_servicio_issste) }}" min="0">
                @error('anios_servicio_issste') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="fecha_alta_issste" class="form-label">Fecha Alta</label>
                <input type="date" class="form-control" id="fecha_alta_issste" name="fecha_alta_issste">
            </div>
            <div class="col-md-3 mb-3">
                <label for="fecha_baja_issste" class="form-label">Fecha Baja</label>
                <input type="date" class="form-control" id="fecha_baja_issste" name="fecha_baja_issste">
            </div>
            <div class="col-md-3 mb-3">
                <label for="nss_issste" class="form-label">NSS ISSSTE *</label>
                <input type="number" class="form-control @error('nss_issste') is-invalid @enderror" id="nss_issste" name="nss_issste"  maxlength="11" placeholder="11 dígitos"
                       value="{{ old('nss_issste', $cliente->nss_issste) }}" min="0">
                @error('nss_issste') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
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
<!-- ==================== ESTILOS ==================== -->
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

<!-- ==================== JAVASCRIPT ==================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ==================== FUNCIÓN CAMPOS INSTITUCIONES ====================
    function toggleInstitucionFields() {
        const instituto = document.getElementById('instituto_id');
        const instituto2 = document.getElementById('instituto2_id');

        // Campos IMSS
        const imssFields = [
            'tramite_id', 'regimen_id', 'modalidad_id',
            'semanas_imss', 'fecha_alta', 'fecha_baja'
        ];

        // Campos ISSSTE
        const isssteFields = [
            'tramite2_id', 'regimen2_id', 'modalidad2_id',
            'anios_servicio_issste', 'nss_issste',
            'fecha_alta_issste', 'fecha_baja_issste'
        ];

        // IMSS
        if (instituto && instituto.value === 'NA') {
            imssFields.forEach(id => {
                const field = document.getElementById(id);
                if (field) {
                    field.disabled = true;
                    field.removeAttribute('required');
                }
            });
        } else if (instituto && instituto.value === 'IMSS') {
            imssFields.forEach(id => {
                const field = document.getElementById(id);
                if (field) {
                    field.disabled = false;
                    field.setAttribute('required', 'required');
                }
            });
        }

        // ISSSTE
        if (instituto2 && instituto2.value === 'NA') {
            isssteFields.forEach(id => {
                const field = document.getElementById(id);
                if (field) {
                    field.disabled = true;
                    field.removeAttribute('required');
                }
            });
        } else if (instituto2 && instituto2.value === 'ISSSTE') {
            isssteFields.forEach(id => {
                const field = document.getElementById(id);
                if (field) {
                    field.disabled = false;
                    field.setAttribute('required', 'required');
                }
            });
        }
    }

    // Ejecutar al cargar y al cambiar
    toggleInstitucionFields();
    document.getElementById('instituto_id')?.addEventListener('change', toggleInstitucionFields);
    document.getElementById('instituto2_id')?.addEventListener('change', toggleInstitucionFields);

    // ==================== VARIABLES GENERALES ====================
    const form = document.querySelector('form');


    // ==================== CONTACTOS DINÁMICOS ====================
    let contactoIndex = document.querySelectorAll('.contacto-item').length;
    const contactosContainer = document.getElementById('contactos-container');
    
    // Obtener opciones de tipos de contacto desde PHP
    const tiposContactoOpciones = `@foreach($tiposContacto as $tipo)
        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
    @endforeach`;

    function agregarContacto() {
        const html = `
        <div class="row contacto-item mb-3">
            <div class="col-md-2">
                <select name="contactos[${contactoIndex}][tipo_contacto_id]" class="form-select contacto-tipo" required>
                    <option value="">Seleccione</option>
                    ${tiposContactoOpciones}
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="contactos[${contactoIndex}][valor]" class="form-control contacto-valor" required>
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="contactos[${contactoIndex}][es_principal]" value="1">
                    <label class="form-check-label">Principal</label>
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm eliminar-contacto">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>`;
        contactosContainer?.insertAdjacentHTML('beforeend', html);
        contactoIndex++;
    }

    document.getElementById('agregar-contacto')?.addEventListener('click', agregarContacto);

    function validarContacto(input) {
        const item = input.closest('.contacto-item');
        if (!item) return true;
        const tipoSelect = item.querySelector('.contacto-tipo');
        const valorInput = item.querySelector('.contacto-valor');
        if (!tipoSelect || !tipoSelect.value) return true;
        const tipo = tipoSelect.options[tipoSelect.selectedIndex]?.text.toLowerCase() || '';
        let valid = true;
        valorInput.classList.remove('is-invalid');
        const errorDiv = valorInput.nextElementSibling;
        if (errorDiv) errorDiv.innerText = '';
        const val = valorInput.value.trim();
        if (tipo.includes('celular') || tipo.includes('telefono')) {
            if (!/^\d{10,13}$/.test(val)) { 
                valorInput.classList.add('is-invalid'); 
                if (errorDiv) errorDiv.innerText = 'Teléfono: 10-13 dígitos.'; 
                valid = false; 
            }
        } else if (tipo.includes('correo')) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
            if (!regex.test(val)) { 
                valorInput.classList.add('is-invalid'); 
                if (errorDiv) errorDiv.innerText = 'Correo inválido.'; 
                valid = false; 
            }
        }
        return valid;
    }

    // ==================== NSS DINÁMICOS ====================
    let nssIndex = document.querySelectorAll('.nss-item').length;
    const nssContainer = document.getElementById('nss-container');

    function validarNss() {
        let valid = true;
        document.querySelectorAll('.nss-input').forEach(input => {
            input.classList.remove('is-invalid');
            const val = input.value.trim();
            const errorDiv = document.getElementById(input.id + '-error');
            if (!/^\d{11}$/.test(val)) {
                input.classList.add('is-invalid');
                if (errorDiv) errorDiv.innerText = 'NSS obligatorio: 11 dígitos numéricos.';
                valid = false;
            } else if (errorDiv) { 
                errorDiv.innerText = ''; 
            }
        });
        return valid;
    }

    function agregarNss() {
        const nuevoIndex = nssIndex;
        const html = `
        <div class="row nss-item mb-3">
            <div class="col-md-2">
                <label for="nss${nuevoIndex}" class="form-label">NSS *</label>
                <input type="text" id="nss${nuevoIndex}" 
                       name="nss[${nuevoIndex}][nss]" 
                       class="form-control nss-input" 
                       required maxlength="11" pattern="\\d{11}" 
                       placeholder="11 dígitos">
                <div class="invalid-feedback" id="nss${nuevoIndex}-error"></div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <div class="form-check">
                    <input type="checkbox" 
                           id="nss${nuevoIndex}-principal" 
                           name="nss[${nuevoIndex}][es_principal]" 
                           class="form-check-input" 
                           value="1">
                    <label class="form-check-label" for="nss${nuevoIndex}-principal">Principal</label>
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm eliminar-nss">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>`;
        nssContainer?.insertAdjacentHTML('beforeend', html);
        nssIndex++;
        
        document.querySelectorAll('.nss-input').forEach(input => {
            input.removeEventListener('input', validarNss);
            input.addEventListener('input', validarNss);
        });
    }

    document.getElementById('agregar-nss')?.addEventListener('click', agregarNss);

    // ==================== CURPs DINÁMICOS ====================
    let curpIndex = document.querySelectorAll('.curp-item').length;
    const curpsContainer = document.getElementById('curps-container');

    function validarCurp() {
        let valid = true;
        document.querySelectorAll('.curp-input').forEach(input => {
            input.classList.remove('is-invalid');
            const val = input.value.trim().toUpperCase();
            const errorDiv = document.getElementById(input.id + '-error');
            if (!/^[A-Z0-9]{18}$/.test(val)) {
                input.classList.add('is-invalid');
                if (errorDiv) errorDiv.innerText = 'CURP obligatorio: 18 caracteres alfanuméricos en mayúsculas.';
                valid = false;
            } else if (errorDiv) { 
                errorDiv.innerText = ''; 
            }
        });
        return valid;
    }

    function agregarCurp() {
        const nuevoIndex = curpIndex;
        const html = `
        <div class="row curp-item mb-2">
            <div class="col-md-5">
                <label for="curp${nuevoIndex}" class="form-label">CURP *</label>
                <input type="text" id="curp${nuevoIndex}" 
                       name="curps[${nuevoIndex}][curp]" 
                       class="form-control curp-input" 
                       required maxlength="18" pattern="[A-Z0-9]{18}" 
                       placeholder="18 caracteres"
                       style="text-transform:uppercase">
                <div class="invalid-feedback" id="curp${nuevoIndex}-error"></div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="form-check">
                    <input type="checkbox" 
                           id="curp${nuevoIndex}-principal" 
                           name="curps[${nuevoIndex}][es_principal]" 
                           class="form-check-input" 
                           value="1">
                    <label class="form-check-label" for="curp${nuevoIndex}-principal">Principal</label>
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm eliminar-curp">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>`;
        curpsContainer?.insertAdjacentHTML('beforeend', html);
        curpIndex++;
        
        document.querySelectorAll('.curp-input').forEach(input => {
            input.removeEventListener('input', validarCurp);
            input.addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
                validarCurp();
            });
        });
    }

    document.getElementById('agregar-curp')?.addEventListener('click', agregarCurp);

    // ==================== RFC DINÁMICOS ====================
    let rfcIndex = document.querySelectorAll('.rfc-item').length;
    const rfcsContainer = document.getElementById('rfcs-container');

    function validarRfc() {
        let valid = true;
        document.querySelectorAll('.rfc-input').forEach(input => {
            input.classList.remove('is-invalid');
            const val = input.value.trim().toUpperCase();
            const errorDiv = document.getElementById(input.id + '-error');
            if (!/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/.test(val)) {
                input.classList.add('is-invalid');
                if (errorDiv) errorDiv.innerText = 'RFC inválido. Debe tener 12 o 13 caracteres.';
                valid = false;
            } else if (errorDiv) { 
                errorDiv.innerText = ''; 
            }
        });
        return valid;
    }

    function agregarRfc() {
        const nuevoIndex = rfcIndex;
        const html = `
        <div class="row rfc-item mb-2">
            <div class="col-md-5 mb-2">
                <label for="rfc${nuevoIndex}" class="form-label">RFC <span class="text-danger">*</span></label>
                <input type="text" id="rfc${nuevoIndex}" 
                       name="rfcs[${nuevoIndex}][rfc]" 
                       class="form-control rfc-input" 
                       maxlength="13" required
                       style="text-transform:uppercase">
                <div class="invalid-feedback" id="rfc${nuevoIndex}-error"></div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="form-check">
                    <input type="checkbox" 
                           id="rfc${nuevoIndex}-principal" 
                           name="rfcs[${nuevoIndex}][es_principal]" 
                           class="form-check-input" 
                           value="1">
                    <label class="form-check-label" for="rfc${nuevoIndex}-principal">Principal</label>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm eliminar-rfc">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>`;
        rfcsContainer?.insertAdjacentHTML('beforeend', html);
        rfcIndex++;
        
        document.querySelectorAll('.rfc-input').forEach(input => {
            input.removeEventListener('input', validarRfc);
            input.addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
                validarRfc();
            });
        });
    }

    document.getElementById('agregar-rfc')?.addEventListener('click', agregarRfc);



    // ==================== VALIDACIONES ====================
    function validarContacto(input) {
        const item = input.closest('.contacto-item');
        if (!item) return true;
        const tipoSelect = item.querySelector('.contacto-tipo');
        const valorInput = item.querySelector('.contacto-valor');
        if (!tipoSelect || !tipoSelect.value) return true;
        const tipo = tipoSelect.options[tipoSelect.selectedIndex]?.text.toLowerCase() || '';
        let valid = true;
        valorInput.classList.remove('is-invalid');
        const errorDiv = valorInput.nextElementSibling;
        if (errorDiv) errorDiv.innerText = '';
        const val = valorInput.value.trim();
        if (tipo.includes('celular') || tipo.includes('telefono')) {
            if (!/^\d{10,13}$/.test(val)) { 
                valorInput.classList.add('is-invalid'); 
                if (errorDiv) errorDiv.innerText = 'Teléfono: 10-13 dígitos.'; 
                valid = false; 
            }
        } else if (tipo.includes('correo')) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
            if (!regex.test(val)) { 
                valorInput.classList.add('is-invalid'); 
                if (errorDiv) errorDiv.innerText = 'Correo inválido.'; 
                valid = false; 
            }
        }
        return valid;
    }
	
    // ==================== ELIMINAR ELEMENTOS DINÁMICOS ====================
    document.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar-contacto')) {
            e.target.closest('.contacto-item')?.remove();
            contactoIndex = document.querySelectorAll('.contacto-item').length;
        }
        if (e.target.closest('.eliminar-nss')) { 
            e.target.closest('.nss-item')?.remove(); 
            nssIndex = document.querySelectorAll('.nss-item').length;
            validarNss();
        }
        if (e.target.closest('.eliminar-curp')) { 
            e.target.closest('.curp-item')?.remove(); 
            curpIndex = document.querySelectorAll('.curp-item').length;
            validarCurp();
        }
        if (e.target.closest('.eliminar-rfc')) { 
            e.target.closest('.rfc-item')?.remove(); 
            rfcIndex = document.querySelectorAll('.rfc-item').length;
            validarRfc();
        }
    });

    // ==================== SUBMIT FORM ====================
    form?.addEventListener('submit', function(e) {
        let todosValidos = true;
        document.querySelectorAll('.contacto-valor').forEach(input => { 
            if (!validarContacto(input)) todosValidos = false; 
        });
        if (!validarNss()) todosValidos = false;
        if (!validarCurp()) todosValidos = false;
        if (!validarRfc()) todosValidos = false;
        if (!todosValidos) e.preventDefault();
    });

    // ==================== EVENT LISTENERS INICIALES ====================
    document.querySelectorAll('.nss-input').forEach(input => {
        input.addEventListener('input', validarNss);
    });
    
    document.querySelectorAll('.curp-input').forEach(input => {
        input.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
            validarCurp();
        });
    });
    
    document.querySelectorAll('.rfc-input').forEach(input => {
        input.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
            validarRfc();
        });
    });


	

});
</script>
@endpush
@endsection