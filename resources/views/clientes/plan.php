@extends('layouts.app')

@section('title', 'Dashboard - ClubPension')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt text-primary me-2"></i>Dashboard
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-calendar me-1"></i> Hoy
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-calendar-alt me-1"></i> Este mes
            </button>
        </div>
        <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Cliente
        </a>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="display-6 fw-bold">{{ $estadisticas['total_clientes'] ?? 0 }}</h2>
                    <p class="mb-0">Total Clientes</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="display-6 fw-bold">{{ $estadisticas['clientes_activos'] ?? 0 }}</h2>
                    <p class="mb-0">Clientes Activos</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="display-6 fw-bold">{{ $estadisticas['clientes_pendientes'] ?? 0 }}</h2>
                    <p class="mb-0">Clientes Pendientes</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="display-6 fw-bold">{{ ($estadisticas['clientes_institucion']['imss'] ?? 0) + ($estadisticas['clientes_institucion']['issste'] ?? 0) }}</h2>
                    <p class="mb-0">Con Pensión</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Distribución por Institución -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-building me-2"></i> Clientes por Institución
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 border rounded">
                            <h3 class="text-primary">{{ $estadisticas['clientes_institucion']['imss'] ?? 0 }}</h3>
                            <p class="mb-0">IMSS</p>
                            <small class="text-muted">Instituto Mexicano del Seguro Social</small>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="p-3 border rounded">
                            <h3 class="text-success">{{ $estadisticas['clientes_institucion']['issste'] ?? 0 }}</h3>
                            <p class="mb-0">ISSSTE</p>
                            <small class="text-muted">Instituto de Seguridad y Servicios Sociales de los Trabajadores del Estado</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2"></i> Resumen de Acciones
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-user-plus text-success me-2"></i> Clientes agregados este mes
                        </span>
                        <span class="badge bg-success rounded-pill">0</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-file-upload text-primary me-2"></i> Documentos pendientes
                        </span>
                        <span class="badge bg-primary rounded-pill">0</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-bell text-warning me-2"></i> Notificaciones
                        </span>
                        <span class="badge bg-warning rounded-pill">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clientes Recientes -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-history me-2"></i> Clientes Recientes
        </div>
        <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-outline-primary">
            Ver todos <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="card-body">
        @if(isset($clientes_recientes) && $clientes_recientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Cliente</th>
                            <th>Nombre Completo</th>
                            <th>Institución</th>
                            <th>Estatus</th>
                            <th>Fecha Alta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes_recientes as $cliente)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark">{{ $cliente->no_cliente ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <strong>{{ $cliente->nombre }} {{ $cliente->apellido_paterno }} {{ $cliente->apellido_materno }}</strong>
                            </td>
                            <td>
                                @if($cliente->instituto)
                                    <span class="badge bg-info">
                                        {{ $cliente->instituto->codigo }}
                                    </span>
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = 'badge-pendiente';
                                    if($cliente->estatus == 'Activo') $badgeClass = 'badge-activo';
                                    elseif($cliente->estatus == 'Suspendido') $badgeClass = 'badge-suspendido';
                                @endphp
                                <span class="badge-estatus {{ $badgeClass }}">
                                    {{ $cliente->estatus }}
                                </span>
                            </td>
                            <td>
                                {{ $cliente->fecha_alta ? \Carbon\Carbon::parse($cliente->fecha_alta)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td>
                                <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5>No hay clientes registrados</h5>
                <p class="text-muted">Comienza agregando tu primer cliente</p>
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Agregar Primer Cliente
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Acciones Rápidas -->
<div class="card mt-4">
    <div class="card-header">
        <i class="fas fa-bolt me-2"></i> Acciones Rápidas
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('clientes.create') }}" class="btn btn-outline-primary w-100 h-100 py-4">
                    <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                    Nuevo Cliente
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="#" class="btn btn-outline-success w-100 h-100 py-4">
                    <i class="fas fa-file-upload fa-2x mb-2"></i><br>
                    Subir Documentos
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="#" class="btn btn-outline-warning w-100 h-100 py-4">
                    <i class="fas fa-chart-line fa-2x mb-2"></i><br>
                    Generar Reporte
                </a>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('perfil') }}" class="btn btn-outline-info w-100 h-100 py-4">
                    <i class="fas fa-cog fa-2x mb-2"></i><br>
                    Mi Perfil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection