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
                            @endphp
                            <span class="badge bg-secondary">{{ $tipoText[$cliente->tipo_cliente] ?? $cliente->tipo_cliente }}</span>
                        </p>
                        <p class="mb-2">
                            <strong>Estatus:</strong> 
                            @php
                                $badgeClass = 'badge-pendiente';
                                if($cliente->estatus == 'Activo') $badgeClass = 'badge-activo';
                                elseif($cliente->estatus == 'Suspendido') $badgeClass = 'badge-suspendido';
                            @endphp
                            <span class="badge-estatus {{ $badgeClass }}">
                                {{ $cliente->estatus }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Fecha de Nacimiento:</strong> 
                            {{ $cliente->fecha_nacimiento ? $cliente->fecha_nacimiento->format('d/m/Y') : 'N/A' }}
                            @if($cliente->edad)
                                <small class="text-muted">({{ $cliente->edad }} años)</small>
                            @endif
                        </p>
                        <p class="mb-2">
                            <strong>Fecha Alta:</strong> 
                            {{ $cliente->fecha_alta ? $cliente->fecha_alta->format('d/m/Y') : 'N/A' }}
                        </p>
                        <p class="mb-0">
                            <strong>Cliente desde:</strong> 
                            {{ $cliente->creado_en ? $cliente->creado_en->format('d/m/Y') : 'N/A' }}
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
        <button class="nav-link active" id="institucional-tab" data-bs-toggle="tab" data-bs-target="#institucional" type="button">
            <i class="fas fa-building me-1"></i> Datos Institucionales
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="economico-tab" data-bs-toggle="tab" data-bs-target="#economico" type="button">
            <i class="fas fa-money-bill-wave me-1"></i> Datos Económicos
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
    <!-- Pestaña Institucional -->
    <div class="tab-pane fade show active" id="institucional" role="tabpanel">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-university me-2"></i> Información Institucional
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
                        
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Semanas IMSS:</strong><br>
                                {{ $cliente->semanas_imss ?? 'N/A' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Semanas ISSSTE:</strong><br>
                                {{ $cliente->semanas_issste ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-id-card me-2"></i> Identificaciones
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>CURP:</strong><br>
                            @if($cliente->curps && $cliente->curps->count() > 0)
                                @foreach($cliente->curps as $curp)
                                    <span class="badge bg-light text-dark">{{ $curp->curp }}</span>
                                    @if($curp->es_principal) <small class="text-muted">(Principal)</small> @endif<br>
                                @endforeach
                            @else
                                <span class="text-muted">No registrado</span>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <strong>NSS:</strong><br>
                            @if($cliente->nsss && $cliente->nsss->count() > 0)
                                @foreach($cliente->nsss as $nss)
                                    <span class="badge bg-light text-dark">{{ $nss->nss }}</span>
                                    @if($nss->es_principal) <small class="text-muted">(Principal)</small> @endif<br>
                                @endforeach
                            @else
                                <span class="text-muted">No registrado</span>
                            @endif
                        </div>
                        
                        <div>
                            <strong>RFC:</strong><br>
                            @if($cliente->rfcs && $cliente->rfcs->count() > 0)
                                @foreach($cliente->rfcs as $rfc)
                                    <span class="badge bg-light text-dark">{{ $rfc->rfc }}</span>
                                    @if($rfc->es_principal) <small class="text-muted">(Principal)</small> @endif<br>
                                @endforeach
                            @else
                                <span class="text-muted">No registrado</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pestaña Económica -->
    <div class="tab-pane fade" id="economico" role="tabpanel">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
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
            
            <div class="col-md-6">
                <div class="card">
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
    
    <!-- Pestaña Documentos -->
    <div class="tab-pane fade" id="documentos" role="tabpanel">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-folder-open me-2"></i> Documentos del Cliente
                </div>
                <button class="btn btn-sm btn-primary">
                    <i class="fas fa-upload me-1"></i> Subir Documento
                </button>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5>Módulo de documentos en desarrollo</h5>
                    <p class="text-muted">Próximamente podrás gestionar documentos del cliente</p>
                    <div class="mt-3">
                        <div class="badge bg-light text-dark me-2">INE</div>
                        <div class="badge bg-light text-dark me-2">CURP</div>
                        <div class="badge bg-light text-dark me-2">RFC</div>
                        <div class="badge bg-light text-dark">Comprobante de domicilio</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pestaña Notas -->
    <div class="tab-pane fade" id="notas" role="tabpanel">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
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
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-2"></i> Información de Auditoría
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Creado por:</strong><br>
                            <span class="text-muted">Sistema</span>
                        </p>
                        <p class="mb-2">
                            <strong>Fecha creación:</strong><br>
                            {{ $cliente->creado_en ? $cliente->creado_en->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                        <p class="mb-0">
                            <strong>Última actualización:</strong><br>
                            {{ $cliente->actualizado_en ? $cliente->actualizado_en->format('d/m/Y H:i') : 'N/A' }}
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
    }
    
    .badge-estatus {
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 500;
    }
</style>
@endpush
@endsection
