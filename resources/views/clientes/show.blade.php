@extends('layouts.app')

@section('title', 'Detalle Cliente - ClubPension')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-circle me-2"></i>Detalle del Cliente
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
        </div>
    </div>
</div>

<!-- Tarjeta de información principal -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h4 class="mb-3">
                    {{ $cliente->nombre }} {{ $cliente->apellido_paterno }} {{ $cliente->apellido_materno }}
                </h4>
                
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>No. Cliente:</strong> 
                            <span class="badge bg-primary">{{ $cliente->no_cliente ?? 'N/A' }}</span>
                        </p>
                        <p class="mb-2">
                            <strong>Tipo de Cliente:</strong> 
                            @php
                                $tipoText = [
                                    'C' => 'Cliente',
                                    'P' => 'Prospecto',
                                    'S' => 'Suspendido',
                                    'B' => 'Baja',
                                    'I' => 'Imposible'
                                ];
                                $tipoBadge = [
                                    'C' => 'bg-success',
                                    'P' => 'bg-info',
                                    'S' => 'bg-warning',
                                    'B' => 'bg-danger',
                                    'I' => 'bg-secondary'
                                ];
                            @endphp
                            <span class="badge {{ $tipoBadge[$cliente->tipo_cliente] ?? 'bg-secondary' }}">
                                {{ $tipoText[$cliente->tipo_cliente] ?? $cliente->tipo_cliente }}
                            </span>
                        </p>
                        <p class="mb-2">
                            <strong>Estatus:</strong> 
                            @php
                                $badgeClass = 'badge-secondary';
                                if($cliente->estatus == 'Activo') $badgeClass = 'badge-success';
                                elseif($cliente->estatus == 'Suspendido') $badgeClass = 'badge-warning';
                                elseif($cliente->estatus == 'Terminado') $badgeClass = 'badge-info';
                                elseif($cliente->estatus == 'Baja') $badgeClass = 'badge-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $cliente->estatus }}
                            </span>
                        </p>
                        <p class="mb-0">
                            <strong>Referencia:</strong> 
                            @if($cliente->referidor)
                                <a href="{{ route('clientes.show', $cliente->referidor) }}" class="text-decoration-none">
                                    {{ $cliente->referidor->no_cliente }} - {{ $cliente->referidor->nombre }} {{ $cliente->referidor->apellido_paterno }}
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Fecha de Nacimiento:</strong> 
                            <!--{{ $cliente->fecha_nacimiento ? $cliente->fecha_nacimiento->format('d/m/Y') : 'N/A' }} -->
							{{ $cliente->fecha_nacimiento ? \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}
                            @if($cliente->edad)
                                <small class="text-muted">({{ $cliente->edad }} años)</small>
                            @endif
                        </p>
                        <p class="mb-2">
                            <strong>Cliente desde:</strong> 
                            {{ $cliente->fecha_contrato ? $cliente->fecha_contrato->format('d/m/Y') : 'N/A' }}
                        </p>
                        <p class="mb-2">
                            <strong>Fecha Captura:</strong> 
                            {{ $cliente->created_at ? $cliente->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                        <p class="mb-0">
                            <strong>Última actualización:</strong> 
                            {{ $cliente->updated_at ? $cliente->updated_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 text-end">
                <!-- QR o avatar del cliente (futuro) -->
                <div class="bg-light rounded p-3 text-center">
                    <i class="fas fa-user-tie fa-4x text-muted mb-2"></i>
                    <p class="mb-0"><small>ID: {{ $cliente->id }}</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pestañas -->
<ul class="nav nav-tabs mb-4" id="clienteTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="personales-tab" data-bs-toggle="tab" data-bs-target="#personales" type="button">
            <i class="fas fa-user me-1"></i> Datos Personales
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="aseguramiento-tab" data-bs-toggle="tab" data-bs-target="#aseguramiento" type="button">
            <i class="fas fa-shield-alt me-1"></i> Datos Aseguramiento
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="economico-tab" data-bs-toggle="tab" data-bs-target="#economico" type="button">
            <i class="fas fa-money-bill-wave me-1"></i> Datos Económicos
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="beneficiarios-tab" data-bs-toggle="tab" data-bs-target="#beneficiarios" type="button">
            <i class="fas fa-users me-1"></i> Beneficiarios
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="documentos-tab" data-bs-toggle="tab" data-bs-target="#documentos" type="button">
            <i class="fas fa-file-alt me-1"></i> Documentos
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="notas-tab" data-bs-toggle="tab" data-bs-target="#notas" type="button">
            <i class="fas fa-sticky-note me-1"></i> Notas
        </button>
    </li>
</ul>

<!-- Contenido de pestañas -->
<div class="tab-content" id="clienteTabsContent">
    <!-- Pestaña 1: Datos Personales -->
    <div class="tab-pane fade show active" id="personales" role="tabpanel">
        <div class="row">
            <!-- Sección 1: Identificaciones -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-id-card me-2"></i> Identificaciones
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>CURP:</strong><br>
                            @if($cliente->curps && $cliente->curps->count() > 0)
                                @foreach($cliente->curps as $curp)
                                    <div class="mb-1">
                                        <span class="badge bg-light text-dark">{{ $curp->curp }}</span>
                                        @if($curp->es_principal) 
                                            <small class="text-success">(Principal)</small>
                                        @else
                                            <small class="text-muted">(Secundario)</small>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <span class="text-muted">No registrado</span>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <strong>RFC:</strong><br>
                            @if($cliente->rfcs && $cliente->rfcs->count() > 0)
                                @foreach($cliente->rfcs as $rfc)
                                    <div class="mb-1">
                                        <span class="badge bg-light text-dark">{{ $rfc->rfc }}</span>
                                        @if($rfc->es_principal) 
                                            <small class="text-success">(Principal)</small>
                                        @else
                                            <small class="text-muted">(Secundario)</small>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <span class="text-muted">No registrado</span>
                            @endif
                        </div>
                        
                        <div>
                            <strong>NSS:</strong><br>
                            @if($cliente->nss && $cliente->nss->count() > 0)
                                @foreach($cliente->nss as $nss)
                                    <div class="mb-1">
                                        <span class="badge bg-light text-dark">{{ $nss->nss }}</span>
                                        @if($nss->es_principal) 
                                            <small class="text-success">(Principal)</small>
                                        @else
                                            <small class="text-muted">(Secundario)</small>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <span class="text-muted">No registrado</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sección 2: Datos de Contacto -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-address-book me-2"></i> Datos de Contacto
                    </div>
                    <div class="card-body">
                        @php
                            $contactosAgrupados = [];
                            foreach($cliente->contactos ?? [] as $contacto) {
                                $contactosAgrupados[$contacto->tipo] = $contacto->valor;
                            }
                        @endphp
                        
                        <div class="mb-3">
                            <strong>Celular1:</strong><br>
                            {{ $contactosAgrupados['celular1'] ?? 'No registrado' }}
                            @if(isset($contactosAgrupados['celular1']))
                                <a href="tel:{{ $contactosAgrupados['celular1'] }}" class="text-decoration-none ms-2">
                                    <i class="fas fa-phone text-success"></i>
                                </a>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <strong>Celular2:</strong><br>
                            {{ $contactosAgrupados['celular2'] ?? 'No registrado' }}
                            @if(isset($contactosAgrupados['celular2']))
                                <a href="tel:{{ $contactosAgrupados['celular2'] }}" class="text-decoration-none ms-2">
                                    <i class="fas fa-phone text-success"></i>
                                </a>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <strong>TelCasa:</strong><br>
                            {{ $contactosAgrupados['tel_casa'] ?? 'No registrado' }}
                        </div>
                        
                        <div class="mb-3">
                            <strong>Correo1:</strong><br>
                            @if(isset($contactosAgrupados['correo1']))
                                <a href="mailto:{{ $contactosAgrupados['correo1'] }}" class="text-decoration-none">
                                    {{ $contactosAgrupados['correo1'] }}
                                </a>
                            @else
                                No registrado
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <strong>Correo2:</strong><br>
                            @if(isset($contactosAgrupados['correo2']))
                                <a href="mailto:{{ $contactosAgrupados['correo2'] }}" class="text-decoration-none">
                                    {{ $contactosAgrupados['correo2'] }}
                                </a>
                            @else
                                No registrado
                            @endif
                        </div>
                        
                        <div>
                            <strong>CorreoPersonal:</strong><br>
                            @if(isset($contactosAgrupados['correo_personal']))
                                <a href="mailto:{{ $contactosAgrupados['correo_personal'] }}" class="text-decoration-none">
                                    {{ $contactosAgrupados['correo_personal'] }}
                                </a>
                            @else
                                No registrado
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pestaña 2: Datos Aseguramiento -->
    <div class="tab-pane fade" id="aseguramiento" role="tabpanel">
        <div class="row">
            <!-- Sección 1: Institución Principal -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-university me-2"></i> Institución Principal
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Institución:</strong><br>
                                @if($cliente->instituto)
                                    <span class="badge bg-info">{{ $cliente->instituto->nombre }}</span>
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <strong>Régimen:</strong><br>
                                @if($cliente->regimen)
                                    {{ $cliente->regimen->nombre }}
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Trámite:</strong><br>
                                @if($cliente->tramite)
                                    {{ $cliente->tramite->nombre }}
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <strong>Modalidad:</strong><br>
                                @if($cliente->modalidad)
                                    {{ $cliente->modalidad->nombre }}
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Semanas IMSS:</strong><br>
                                {{ $cliente->semanas_imss ?? 'N/A' }}
                            </div>
                            <div class="col-md-6">
                                <strong>FechaAlta:</strong><br>
                                {{ $cliente->fecha_alta ? $cliente->fecha_alta->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <strong>FechaBaja:</strong><br>
                                {{ $cliente->fecha_baja ? $cliente->fecha_baja->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sección 2: Institución 2 (ISSSTE) -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-building me-2"></i> Institución 2
                    </div>
                    <div class="card-body">
                        @if($cliente->instituto2_id == 14)
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Institución2:</strong><br>
                                    @if($cliente->instituto2)
                                        <span class="badge bg-info">{{ $cliente->instituto2->nombre }}</span>
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <strong>Régimen2:</strong><br>
                                    @if($cliente->regimen2)
                                        {{ $cliente->regimen2->nombre }}
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Trámite2:</strong><br>
                                    @if($cliente->tramite2)
                                        {{ $cliente->tramite2->nombre }}
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <strong>Modalidad2:</strong><br>
                                    {{ $cliente->modalidad_issste == 'NA' ? 'No Aplica' : 
                                    ($cliente->modalidad_issste == 'CV' ? 'Continuación Voluntaria' : 
                                    $cliente->modalidad_issste) }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Años de Servicio:</strong><br>
                                    {{ $cliente->anios_servicio_issste ?? 'N/A' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>NSSIssste:</strong><br>
                                    {{ $cliente->nss_issste ?? 'N/A' }}
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>FechaAlta_ModIssste:</strong><br>
                                    {{ $cliente->fecha_alta_issste ? $cliente->fecha_alta_issste->format('d/m/Y') : 'N/A' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>FechaBaja_ModIssste:</strong><br>
                                    {{ $cliente->fecha_baja_issste ? $cliente->fecha_baja_issste->format('d/m/Y') : 'N/A' }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-times-circle fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No se ha registrado Institución 2</p>
                                <small>Seleccione "N/A" en la edición si no tiene segunda institución</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pestaña 3: Datos Económicos -->
    <div class="tab-pane fade" id="economico" role="tabpanel">
        <div class="row">
            <!-- Sección 1: Información Económica -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-chart-line me-2"></i> Información Económica
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded text-center">
                                    <h6 class="text-muted">Pensión Default</h6>
                                    <h4 class="text-primary">
                                        ${{ number_format($cliente->pension_default ?? 0, 2) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded text-center">
                                    <h6 class="text-muted">Pensión Normal</h6>
                                    <h4 class="text-success">
                                        ${{ number_format($cliente->pension_normal ?? 0, 2) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded text-center">
                                    <h6 class="text-muted">Comisión</h6>
                                    <h4 class="text-warning">
                                        ${{ number_format($cliente->comision ?? 0, 2) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 border rounded text-center">
                                    <h6 class="text-muted">Honorarios</h6>
                                    <h4 class="text-info">
                                        ${{ number_format($cliente->honorarios ?? 0, 2) }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sección 2: Historial (Futuro) -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-history me-2"></i> Historial (Futuro)
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Módulo de reportes económicos en desarrollo</p>
                            <small>Próximamente: gráficos y estadísticas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pestaña 4: Beneficiarios -->
    <div class="tab-pane fade" id="beneficiarios" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-users me-2"></i> Beneficiarios
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                    <h5>Módulo de Beneficiarios en desarrollo</h5>
                    <p class="text-muted">Próximamente podrás gestionar los beneficiarios del cliente</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pestaña 5: Documentos -->
    <div class="tab-pane fade" id="documentos" role="tabpanel">
        <div class="row">
            <!-- Sección 1: Documentos del Cliente -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-folder-open me-2"></i> Documentos del Cliente
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5>Módulo de documentos en desarrollo</h5>
                            <p class="text-muted">Próximamente podrás gestionar documentos del cliente</p>
                            <div class="mt-3">
                                <div class="badge bg-light text-dark me-2 mb-2">INE</div>
                                <div class="badge bg-light text-dark me-2 mb-2">CURP</div>
                                <div class="badge bg-light text-dark me-2 mb-2">RFC</div>
                                <div class="badge bg-light text-dark mb-2">Comprobante de domicilio</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sección 2: Documentos del Beneficiario -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-folder me-2"></i> Documentos del Beneficiario
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i class="fas fa-file-medical fa-3x text-muted mb-3"></i>
                            <h5>Módulo en desarrollo</h5>
                            <p class="text-muted">Próximamente podrás gestionar documentos del beneficiario</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pestaña 6: Notas -->
    <div class="tab-pane fade" id="notas" role="tabpanel">
        <div class="row">
            <!-- Sección 1: Notas del Cliente -->
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-comments me-2"></i> Notas del Cliente
                    </div>
                    <div class="card-body">
                        <div class="text-center py-5">
                            <i class="fas fa-sticky-note fa-3x text-muted mb-3"></i>
                            <h5>Módulo de notas en desarrollo</h5>
                            <p class="text-muted">Próximamente podrás agregar notas de seguimiento</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sección 2: Información de Auditoría -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-2"></i> Información de Auditoría
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Creado por:</strong><br>
                            <span class="text-muted">
                                @if($cliente->creadoPor)
                                    {{ $cliente->creadoPor->nombre }} {{ $cliente->creadoPor->apellidos }}
                                @else
                                    Sistema
                                @endif
                            </span>
                        </p>
                        <p class="mb-2">
                            <strong>Fecha creación:</strong><br>
                            {{ $cliente->created_at ? $cliente->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                        <p class="mb-2">
                            <strong>Actualizado por:</strong><br>
                            <span class="text-muted">
                                @if($cliente->actualizadoPor)
                                    {{ $cliente->actualizadoPor->nombre }} {{ $cliente->actualizadoPor->apellidos }}
                                @elseif($cliente->creadoPor)
                                    {{ $cliente->creadoPor->nombre }} {{ $cliente->creadoPor->apellidos }}
                                @else
                                    Sistema
                                @endif
                            </span>
                        </p>
                        <p class="mb-0">
                            <strong>Última actualización:</strong><br>
                            {{ $cliente->updated_at ? $cliente->updated_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones rápidas -->
<div class="card mt-4">
    <div class="card-header">
        <i class="fas fa-bolt me-2"></i> Acciones Rápidas
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 col-sm-6 mb-2">
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-edit me-1"></i> Editar Cliente
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <button class="btn btn-outline-success w-100" onclick="alert('Próximamente')">
                    <i class="fas fa-file-export me-1"></i> Exportar PDF
                </button>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <button class="btn btn-outline-warning w-100" onclick="alert('Próximamente')">
                    <i class="fas fa-envelope me-1"></i> Enviar Email
                </button>
            </div>
            <div class="col-md-3 col-sm-6 mb-2">
                <form action="{{ route('clientes.destroy', $cliente) }}" 
                      method="POST" 
                      onsubmit="return confirm('¿Estás seguro de eliminar este cliente? Esta acción no se puede deshacer.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .nav-tabs .nav-link {
        color: #495057;
        font-weight: 500;
    }
    
    .nav-tabs .nav-link.active {
        border-bottom-color: #fff;
        font-weight: 600;
        color: #0d6efd;
    }
    
    .badge-estatus {
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 500;
    }
    
    .card {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Activar primera pestaña
    const firstTab = document.getElementById('personales-tab');
    if (firstTab) {
        firstTab.click();
    }
});
</script>
@endpush
@endsection