@extends('layouts.app')

@section('title', 'Clientes - ClubPension')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users me-2"></i>Clientes
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo Cliente
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('clientes.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Nombre, CURP, NSS...">
            </div>
            <div class="col-md-3">
                <label for="estatus" class="form-label">Estatus</label>
                <select class="form-select" id="estatus" name="estatus">
                    <option value="">Todos</option>
                    <option value="Activo" {{ request('estatus') == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="pendiente" {{ request('estatus') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Suspendido" {{ request('estatus') == 'Suspendido' ? 'selected' : '' }}>Suspendido</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="instituto" class="form-label">Institución</label>
                <select class="form-select" id="instituto" name="instituto_id">
                    <option value="">Todas</option>
                    @foreach($institutos as $instituto)
                        <option value="{{ $instituto->id }}" {{ request('instituto_id') == $instituto->id ? 'selected' : '' }}>
                            {{ $instituto->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Clientes -->
<div class="card">
    <div class="card-body">
        @if($clientes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Cliente</th>
                            <th>Nombre Completo</th>
                            <th>CURP</th>
                            <th>Institución</th>
                            <th>Estatus</th>
                            <th>Fecha Alta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark">{{ $cliente->no_cliente ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <strong>{{ $cliente->nombre }} {{ $cliente->apellido_paterno }} {{ $cliente->apellido_materno }}</strong>
                            </td>
                            <td>
                                @if($cliente->curps && $cliente->curps->count() > 0)
                                    {{ $cliente->curps->first()->curp ?? 'N/A' }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
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
                                <div class="btn-group" role="group">
                                    <a href="{{ route('clientes.show', $cliente) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clientes.edit', $cliente) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('clientes.destroy', $cliente) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Mostrando {{ $clientes->firstItem() }} - {{ $clientes->lastItem() }} de {{ $clientes->total() }} clientes
                </div>
                <div>
                    {{ $clientes->links() }}
                </div>
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

<!-- Estadísticas rápidas -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Total</h5>
                <h2 class="mb-0">{{ $totalClientes }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Activos</h5>
                <h2 class="mb-0">{{ $activosCount }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Pendientes</h5>
                <h2 class="mb-0">{{ $pendientesCount }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Con IMSS</h5>
                <h2 class="mb-0">{{ $imssCount }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection
