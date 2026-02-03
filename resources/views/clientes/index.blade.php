@extends('layouts.app')

@section('title', 'Clientes - ClubPension')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-users me-2"></i>Clientes
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Nuevo Cliente
                </a>
                <a href="{{ route('prospectos.index') }}" class="btn btn-outline-info">
                    <i class="fas fa-user-friends me-1"></i> Ver Prospectos
                </a>
            </div>
        </div>
    </div>

    <!-- 📊 ESTADÍSTICAS RÁPIDAS -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Clientes</h6>
                            <h2 class="mb-0">{{ $totalClientes }}</h2>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Activos</h6>
                            <h2 class="mb-0">{{ $activosCount }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">IMSS</h6>
                            <h2 class="mb-0">{{ $imssCount }}</h2>
                        </div>
                        <i class="fas fa-hospital fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Pendientes</h6>
                            <h2 class="mb-0">{{ $pendientesCount }}</h2>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔍 FILTROS DE BÚSQUEDA -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
            </h6>
        </div>
        <div class="card-body">
            <form id="searchForm" method="GET" action="{{ route('clientes.index') }}" class="row g-3">
                <!-- 🔍 BÚSQUEDA POR TEXTO (AUTOCOMPLETE) -->
                <div class="col-md-4 position-relative">
                    <label for="searchInput" class="form-label">
                        <i class="fas fa-search me-1"></i>Buscar cliente
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" 
                               class="form-control" 
                               id="searchInput"
                               name="search"
                               placeholder="No. Cliente, Nombre, Apellidos, CURP o NSS"
                               value="{{ request('search') }}"
                               autocomplete="off">
                        @if(request('search'))
                            <button type="button" 
                                    class="btn btn-outline-secondary" 
                                    id="clearSearch"
                                    title="Limpiar búsqueda">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>
                    <small class="text-muted d-block mt-1">
                        Busca por: No. Cliente, Nombre, Apellido Paterno, Apellido Materno, CURP o NSS
                    </small>
                    
                    <!-- 🔍 RESULTADOS DE AUTOCOMPLETE -->
                    <div id="searchResults" 
                         class="position-absolute bg-white border rounded shadow mt-1" 
                         style="display: none; z-index: 1050; width: 100%; max-width: 500px; max-height: 400px; overflow-y: auto;">
                        <!-- Los resultados de autocomplete se cargarán aquí -->
                    </div>
                </div>
                
                <!-- 📊 FILTRO POR ESTATUS -->
                <div class="col-md-3">
                    <label for="estatusFilter" class="form-label">
                        <i class="fas fa-flag me-1"></i>Estatus
                    </label>
                    <select class="form-select" 
                            id="estatusFilter"
                            name="estatus">
                        <option value="todos" {{ request('estatus') == 'todos' || !request('estatus') ? 'selected' : '' }}>
                            Todos los estatus
                        </option>
                        <option value="Activo" {{ request('estatus') == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Suspendido" {{ request('estatus') == 'Suspendido' ? 'selected' : '' }}>Suspendido</option>
                        <option value="Terminado" {{ request('estatus') == 'Terminado' ? 'selected' : '' }}>Terminado</option>
                        <option value="Baja" {{ request('estatus') == 'Baja' ? 'selected' : '' }}>Baja</option>
                    </select>
                </div>
                
                <!-- 🏢 FILTRO POR INSTITUCIÓN -->
                <div class="col-md-3">
                    <label for="institutoFilter" class="form-label">
                        <i class="fas fa-building me-1"></i>Institución
                    </label>
                    <select class="form-select" 
                            id="institutoFilter"
                            name="instituto_id">
                        <option value="todos" {{ request('instituto_id') == 'todos' || !request('instituto_id') ? 'selected' : '' }}>
                            Todas las instituciones
                        </option>
                        @foreach($institutos as $instituto)
                            <option value="{{ $instituto->id }}" 
                                {{ request('instituto_id') == $instituto->id ? 'selected' : '' }}>
                                {{ $instituto->nombre }} ({{ $instituto->codigo }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- 🔘 BOTONES DE ACCIÓN -->
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-grid gap-2 w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- 📊 RESUMEN DE FILTROS ACTIVOS -->
        @if(request('search') || (request('estatus') && request('estatus') != 'todos') || (request('instituto_id') && request('instituto_id') != 'todos'))
        <div class="card-footer bg-light">
            <div class="d-flex align-items-center">
                <small class="me-3">
                    <i class="fas fa-filter me-1"></i> Filtros aplicados:
                </small>
                <div class="d-flex gap-2">
                    @if(request('search'))
                    <span class="badge bg-info">
                        <i class="fas fa-search me-1"></i> Texto: "{{ request('search') }}"
                    </span>
                    @endif
                    @if(request('estatus') && request('estatus') != 'todos')
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-flag me-1"></i> Estatus: {{ request('estatus') }}
                    </span>
                    @endif
                    @if(request('instituto_id') && request('instituto_id') != 'todos')
                    @php
                        $institutoSeleccionado = $institutos->firstWhere('id', request('instituto_id'));
                    @endphp
                    @if($institutoSeleccionado)
                    <span class="badge bg-success">
                        <i class="fas fa-building me-1"></i> Institución: {{ $institutoSeleccionado->codigo }}
                    </span>
                    @endif
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- 📋 TABLA DE CLIENTES -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i> Lista de Clientes
                <span class="badge bg-secondary ms-2">{{ $clientes->total() }} registros</span>
            </h5>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-1"></i> Exportar
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('clientes.exportar') }}">
                            <i class="fas fa-file-csv me-2"></i> CSV
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No. Cliente</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>CURP</th>
                            <th>NSS</th>
                            <th>Institución</th>
                            <th>Estatus</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                        <tr>
                            <td>
                                <strong>{{ $cliente->no_cliente ?? 'N/A' }}</strong>
                            </td>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->apellido_paterno ?? '-' }}</td>
                            <td>{{ $cliente->apellido_materno ?? '-' }}</td>
                            <td>
                                @if($cliente->curps->count() > 0)
                                    @php
                                        $curpPrincipal = $cliente->curps->where('es_principal', true)->first();
                                    @endphp
                                    <span class="badge bg-info text-dark">{{ $curpPrincipal->curp ?? $cliente->curps->first()->curp }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($cliente->nss->count() > 0)
                                    @php
                                        $nssPrincipal = $cliente->nss->where('es_principal', true)->first();
                                    @endphp
                                    <span class="badge bg-secondary">{{ $nssPrincipal->nss ?? $cliente->nss->first()->nss }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    @if($cliente->instituto)
                                        <span class="badge bg-primary">{{ $cliente->instituto->codigo }}</span>
                                    @endif
                                    @if($cliente->instituto2)
                                        <span class="badge bg-warning text-dark mt-1">{{ $cliente->instituto2->codigo }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $estatusClass = 'bg-secondary';
                                    if ($cliente->estatus === 'Activo') $estatusClass = 'bg-success';
                                    elseif ($cliente->estatus === 'Suspendido') $estatusClass = 'bg-warning text-dark';
                                    elseif ($cliente->estatus === 'Baja') $estatusClass = 'bg-danger';
                                    elseif ($cliente->estatus === 'Terminado') $estatusClass = 'bg-dark';
                                @endphp
                                <span class="badge {{ $estatusClass }}">{{ $cliente->estatus ?? 'N/A' }}</span>
                            </td>
                            <td>
                                {{ $cliente->fecha_creacion_formateada }}
                                <br>
                                <small class="text-muted">{{ $cliente->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('clientes.show', $cliente->id) }}" 
                                       class="btn btn-outline-info" 
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Editar"
                                       @if($cliente->tipo_cliente !== 'C') disabled @endif>
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('clientes.cambiar-estatus', $cliente->id) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Cambiar estatus">
                                        <i class="fas fa-exchange-alt"></i>
                                    </a>
                                    <form action="{{ route('clientes.destroy', $cliente->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger" 
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <h5>No hay clientes registrados</h5>
                                    <p class="mb-3">Los clientes aparecerán aquí después de convertir prospectos.</p>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('prospectos.index') }}" class="btn btn-primary">
                                            <i class="fas fa-user-friends me-1"></i> Ver Prospectos
                                        </a>
                                        <a href="{{ route('clientes.create') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-plus me-1"></i> Crear Nuevo
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 📄 PAGINACIÓN -->
            @if($clientes->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Mostrando {{ $clientes->firstItem() ?? 0 }} - {{ $clientes->lastItem() ?? 0 }} de {{ $clientes->total() }} clientes
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Paginación personalizada manteniendo filtros --}}
                        @if($clientes->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">« Anterior</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $clientes->previousPageUrl() }}" rel="prev">« Anterior</a>
                            </li>
                        @endif

                        @foreach($clientes->getUrlRange(max(1, $clientes->currentPage() - 2), min($clientes->lastPage(), $clientes->currentPage() + 2)) as $page => $url)
                            @if($page == $clientes->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        @if($clientes->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $clientes->nextPageUrl() }}" rel="next">Siguiente »</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Siguiente »</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>

    <!-- 📊 INFORMACIÓN ADICIONAL -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i> Información
                    </h6>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Vista de Clientes:</strong> Esta página muestra <strong>solo registros con tipo "Cliente"</strong>.
                    </p>
                    <p>
                        Para ver prospectos, imposibles, bajas y suspendidos, visita la vista de <strong>Prospectos</strong>.
                    </p>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Tip:</strong> Los registros nuevos se crean como Prospectos y luego se convierten a Clientes.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-search me-2"></i> Ayuda de Búsqueda
                    </h6>
                </div>
                <div class="card-body">
                    <p>Puedes buscar por:</p>
                    <ul class="mb-0">
                        <li><strong>No. Cliente:</strong> CP-24010001</li>
                        <li><strong>Nombre:</strong> Juan, María, etc.</li>
                        <li><strong>Apellidos:</strong> Pérez, García, etc.</li>
                        <li><strong>CURP:</strong> GOFV681210HCHRRL00</li>
                        <li><strong>NSS:</strong> 12345678901</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const searchInput = document.getElementById('searchInput');
    const estatusFilter = document.getElementById('estatusFilter');
    const institutoFilter = document.getElementById('institutoFilter');
    const searchForm = document.getElementById('searchForm');
    const resultadosContainer = document.getElementById('searchResults');
    
    // Variables de control
    let searchTimeout;
    
    // 🔍 FUNCIÓN PARA BUSCAR CLIENTES (AUTOCOMPLETE)
    function buscarClientesAutocomplete() {
        const searchTerm = searchInput.value.trim();
        const estatus = estatusFilter.value;
        const institutoId = institutoFilter.value;
        
        // Solo buscar si hay al menos 2 caracteres
        if (searchTerm.length >= 2) {
            // Mostrar loading
            resultadosContainer.innerHTML = `
                <div class="text-center p-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Buscando...</span>
                    </div>
                    <span class="ms-2">Buscando clientes...</span>
                </div>
            `;
            resultadosContainer.style.display = 'block';
            
            // Hacer petición AJAX con TODOS los filtros
            fetch('/clientes/search?' + new URLSearchParams({
                q: searchTerm,
                estatus: estatus,
                instituto_id: institutoId
            }), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    mostrarResultadosAutocomplete(data.clientes);
                } else {
                    mostrarErrorAutocomplete('Error en la búsqueda');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarErrorAutocomplete('Error al conectar con el servidor');
            });
        } else {
            // Ocultar resultados si hay menos de 2 caracteres
            resultadosContainer.style.display = 'none';
        }
    }
    
    // 📋 FUNCIÓN PARA MOSTRAR RESULTADOS AUTOCOMPLETE
    function mostrarResultadosAutocomplete(clientes) {
        if (clientes.length === 0) {
            resultadosContainer.innerHTML = `
                <div class="p-3 text-center text-muted">
                    <i class="fas fa-search me-2"></i>
                    No se encontraron clientes
                </div>
            `;
            return;
        }
        
        let html = '<div class="list-group list-group-flush">';
        
        clientes.forEach(cliente => {
            // Información a mostrar en cada resultado
            const infoLines = [];
            
            if (cliente.no_cliente && cliente.no_cliente !== 'N/A') {
                infoLines.push(`<div><strong>No. Cliente:</strong> ${cliente.no_cliente}</div>`);
            }
            
            if (cliente.curp) {
                infoLines.push(`<div><strong>CURP:</strong> ${cliente.curp}</div>`);
            }
            
            if (cliente.nss) {
                infoLines.push(`<div><strong>NSS:</strong> ${cliente.nss}</div>`);
            }
            
            // Color del badge según estatus
            let badgeClass = 'bg-secondary';
            if (cliente.estatus === 'Activo') badgeClass = 'bg-success';
            else if (cliente.estatus === 'Suspendido') badgeClass = 'bg-warning text-dark';
            else if (cliente.estatus === 'Baja') badgeClass = 'bg-danger';
            
            html += `
                <a href="${cliente.show_url}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between align-items-start">
                        <div class="flex-grow-1 me-3">
                            <h6 class="mb-1">${cliente.nombre_completo}</h6>
                            <div class="small text-muted">
                                ${infoLines.join('')}
                            </div>
                        </div>
                        <div>
                            <span class="badge ${badgeClass}">${cliente.estatus}</span>
                        </div>
                    </div>
                </a>
            `;
        });
        
        html += '</div>';
        
        // Agregar contador de resultados
        html += `
            <div class="p-2 border-top text-center bg-light">
                <small class="text-muted">${clientes.length} cliente(s) encontrado(s)</small>
            </div>
        `;
        
        resultadosContainer.innerHTML = html;
    }
    
    // ❌ FUNCIÓN PARA MOSTRAR ERROR EN AUTOCOMPLETE
    function mostrarErrorAutocomplete(mensaje) {
        resultadosContainer.innerHTML = `
            <div class="alert alert-danger m-2" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${mensaje}
            </div>
        `;
    }
    
    // ⏰ EVENTOS DE BÚSQUEDA CON DEBOUNCE (300ms)
    searchInput.addEventListener('input', function() {
        // Limpiar timeout anterior
        clearTimeout(searchTimeout);
        
        // Establecer nuevo timeout (debounce)
        searchTimeout = setTimeout(() => {
            buscarClientesAutocomplete();
        }, 300);
    });
    
    // 🔄 ACTUALIZAR AUTOCOMPLETE AL CAMBIAR FILTROS
    estatusFilter.addEventListener('change', function() {
        if (searchInput.value.trim().length >= 2) {
            buscarClientesAutocomplete();
        }
    });
    
    institutoFilter.addEventListener('change', function() {
        if (searchInput.value.trim().length >= 2) {
            buscarClientesAutocomplete();
        }
    });
    
    // 👁️ OCULTAR RESULTADOS AL HACER CLIC FUERA
    document.addEventListener('click', function(event) {
        if (!searchForm.contains(event.target)) {
            resultadosContainer.style.display = 'none';
        }
    });
    
    // 🔍 ENFOQUE EN CAMPO DE BÚSQUEDA
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2 && resultadosContainer.children.length > 0) {
            resultadosContainer.style.display = 'block';
        }
    });
    
    // 📱 LIMPIAR BÚSQUEDA Y FILTROS
    const clearSearchBtn = document.getElementById('clearSearch');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            resultadosContainer.style.display = 'none';
        });
    }
    
    // 🔘 ENVIAR FORMULARIO AL PRESIONAR ENTER
    searchInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            searchForm.submit();
        }
    });
    
    // 📊 MOSTRAR/OCULTAR INFORMACIÓN DE FILTROS
    const filterBadges = document.querySelectorAll('.badge');
    filterBadges.forEach(badge => {
        badge.addEventListener('click', function() {
            const filterType = this.getAttribute('data-filter-type');
            const filterValue = this.getAttribute('data-filter-value');
            
            if (filterType === 'estatus') {
                estatusFilter.value = filterValue;
            } else if (filterType === 'instituto') {
                institutoFilter.value = filterValue;
            }
            
            searchForm.submit();
        });
    });
});
</script>
@endpush
