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

<!-- Filtros y Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('clientes.index') }}" id="searchForm" class="row g-3">
            <!-- Campo de búsqueda principal con autocomplete -->
            <div class="col-md-4 position-relative">
                <label for="search" class="form-label">Buscar Cliente</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nombre, NSS, CURP, RFC, No. Cliente..."
                           autocomplete="off"
                           aria-describedby="clearSearch">
                    <button class="btn btn-outline-secondary" type="button" id="clearSearch" title="Limpiar búsqueda">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Contenedor para resultados del autocomplete -->
                <div id="searchResults" class="position-absolute w-100 bg-white border rounded shadow-sm mt-1" style="display: none; z-index: 1000; max-height: 400px; overflow-y: auto;"></div>
            </div>

            <!-- Filtro de Estatus -->
            <div class="col-md-2">
                <label for="estatus" class="form-label">Estatus</label>
                <select class="form-select" id="estatus" name="estatus">
                    <option value="">Todos</option>
                    <option value="Activo" {{ request('estatus') == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="pendiente" {{ request('estatus') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Suspendido" {{ request('estatus') == 'Suspendido' ? 'selected' : '' }}>Suspendido</option>
                    <option value="Terminado" {{ request('estatus') == 'Terminado' ? 'selected' : '' }}>Terminado</option>
                    <option value="Baja" {{ request('estatus') == 'Baja' ? 'selected' : '' }}>Baja</option>
                </select>
            </div>

            <!-- Filtro de Institución (IMSS/ISSSTE) -->
            <div class="col-md-2">
                <label for="instituto_id" class="form-label">Institución</label>
                <select class="form-select" id="instituto_id" name="instituto_id">
                    <option value="">Todas</option>
                    @foreach($institutos as $instituto)
                        <option value="{{ $instituto->id }}" {{ request('instituto_id') == $instituto->id ? 'selected' : '' }}>
                            {{ $instituto->codigo }} - {{ $instituto->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro de Tipo de Cliente -->
            <div class="col-md-2">
                <label for="tipo_cliente" class="form-label">Tipo Cliente</label>
                <select class="form-select" id="tipo_cliente" name="tipo_cliente">
                    <option value="">Todos</option>
                    <option value="Cliente Interno" {{ request('tipo_cliente') == 'Cliente Interno' ? 'selected' : '' }}>Cliente Interno</option>
                    <option value="Cliente Externo" {{ request('tipo_cliente') == 'Cliente Externo' ? 'selected' : '' }}>Cliente Externo</option>
                    <option value="Otro" {{ request('tipo_cliente') == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <!-- Botones de acción -->
            <div class="col-md-2 d-flex align-items-end">
                <div class="btn-group w-100" role="group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Limpiar
                    </a>
                </div>
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
                            <th>NSS</th>
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
                                    @php
                                        $curpPrincipal = $cliente->curps->where('es_principal', true)->first();
                                    @endphp
                                    @if($curpPrincipal)
                                        <span class="text-monospace">{{ $curpPrincipal->curp }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($cliente->nss && $cliente->nss->count() > 0)
                                    @php
                                        $nssPrincipal = $cliente->nss->where('es_principal', true)->first();
                                    @endphp
                                    @if($nssPrincipal)
                                        <span class="text-monospace">{{ $nssPrincipal->nss }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    @if($cliente->instituto)
                                        <span class="badge bg-primary mb-1">
                                            {{ $cliente->instituto->codigo }}
                                        </span>
                                    @endif
                                    @if($cliente->instituto2)
                                        <span class="badge bg-warning">
                                            {{ $cliente->instituto2->codigo }}
                                        </span>
                                    @endif
                                    @if(!$cliente->instituto && !$cliente->instituto2)
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeClass = 'badge bg-secondary';
                                    if($cliente->estatus == 'Activo') $badgeClass = 'badge bg-success';
                                    elseif($cliente->estatus == 'pendiente') $badgeClass = 'badge bg-warning';
                                    elseif($cliente->estatus == 'Suspendido') $badgeClass = 'badge bg-danger';
                                    elseif($cliente->estatus == 'Terminado') $badgeClass = 'badge bg-info';
                                    elseif($cliente->estatus == 'Baja') $badgeClass = 'badge bg-dark';
                                @endphp
                                <span class="{{ $badgeClass }}">
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
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'estatus', 'instituto_id', 'tipo_cliente']))
                        No se encontraron clientes con los filtros aplicados
                    @else
                        Comienza agregando tu primer cliente
                    @endif
                </p>
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Agregar Cliente
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

<!-- Scripts para búsqueda en tiempo real -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const searchResults = document.getElementById('searchResults');
    const clearSearchBtn = document.getElementById('clearSearch');
    const searchForm = document.getElementById('searchForm');
    let searchTimeout = null;
    let currentSearchTerm = '';
    
    // ========== FUNCIÓN PRINCIPAL DE AUTOCOMPLETE ==========
    function performAutoComplete(searchTerm) {
        if (searchTerm.length < 2) {
            hideSearchResults();
            return;
        }
        
        if (searchTerm === currentSearchTerm) {
            return; // Evita búsquedas duplicadas del mismo término
        }
        
        currentSearchTerm = searchTerm;
        
        // Obtener valores actuales de los filtros para enviarlos en la búsqueda
        const estatus = document.getElementById('estatus').value;
        const institutoId = document.getElementById('instituto_id').value;
        const tipoCliente = document.getElementById('tipo_cliente').value;
        
        // Mostrar indicador de carga
        searchResults.innerHTML = `
            <div class="p-3 text-center">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Buscando...</span>
                </div>
                <span class="ms-2">Buscando clientes...</span>
            </div>
        `;
        searchResults.style.display = 'block';
        
        // Realizar petición AJAX al endpoint de búsqueda
        fetch(`{{ route('clientes.search') }}?q=${encodeURIComponent(searchTerm)}&estatus=${estatus}&instituto_id=${institutoId}&tipo_cliente=${tipoCliente}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.clientes && data.clientes.length > 0) {
                    renderSearchResults(data.clientes);
                } else {
                    showNoResults();
                }
            })
            .catch(error => {
                console.error('Error en búsqueda:', error);
                showError();
            });
    }
    
    // ========== FUNCIÓN PARA RENDERIZAR RESULTADOS ==========
    function renderSearchResults(clientes) {
        let resultsHtml = '<div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">';
        
        clientes.forEach(cliente => {
            let badges = [];
            let infoItems = [];
            
            // Badges de institución
            if (cliente.institucion) {
                badges.push(`<span class="badge bg-primary me-1">${cliente.institucion}</span>`);
            }
            if (cliente.institucion2) {
                badges.push(`<span class="badge bg-warning me-1">${cliente.institucion2}</span>`);
            }
            
            // Información adicional
            if (cliente.curp_principal) {
                infoItems.push(`<div><small class="text-muted">CURP:</small> <span class="fw-bold">${cliente.curp_principal}</span></div>`);
            }
            if (cliente.rfc_principal) {
                infoItems.push(`<div><small class="text-muted">RFC:</small> <span class="fw-bold">${cliente.rfc_principal}</span></div>`);
            }
            if (cliente.nss_principal) {
                infoItems.push(`<div><small class="text-muted">NSS:</small> <span class="fw-bold">${cliente.nss_principal}</span></div>`);
            }
            
            resultsHtml += `
                <a href="${cliente.show_url}" class="list-group-item list-group-item-action search-result-item" data-id="${cliente.id}">
                    <div class="d-flex w-100 justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 text-primary">${cliente.nombre_completo}</h6>
                            <div class="mb-1">
                                <small class="text-muted">No. Cliente:</small>
                                <span class="fw-bold ms-1">${cliente.no_cliente || 'N/A'}</span>
                            </div>
                            ${infoItems.join('')}
                        </div>
                        <div class="text-end">
                            ${badges.join('')}
                            <div class="mt-1">
                                <span class="badge ${getStatusBadgeClass(cliente.estatus)}">${cliente.estatus}</span>
                            </div>
                        </div>
                    </div>
                </a>
            `;
        });
        
        resultsHtml += '</div>';
        
        // Agregar opción para buscar con los filtros actuales
        resultsHtml += `
            <div class="border-top p-2 bg-light">
                <a href="{{ route('clientes.index') }}?search=${encodeURIComponent(currentSearchTerm)}&estatus=${document.getElementById('estatus').value}&instituto_id=${document.getElementById('instituto_id').value}&tipo_cliente=${document.getElementById('tipo_cliente').value}" 
                   class="btn btn-sm btn-primary w-100">
                   <i class="fas fa-search me-1"></i> Ver todos los resultados (${clientes.length})
                </a>
            </div>
        `;
        
        searchResults.innerHTML = resultsHtml;
        searchResults.style.display = 'block';
    }
    
    // ========== FUNCIONES AUXILIARES ==========
    function showNoResults() {
        searchResults.innerHTML = `
            <div class="p-3 text-center text-muted">
                <i class="fas fa-search fa-lg mb-2"></i>
                <p class="mb-0">No se encontraron clientes</p>
                <small>Intenta con otros términos de búsqueda</small>
            </div>
        `;
        searchResults.style.display = 'block';
    }
    
    function showError() {
        searchResults.innerHTML = `
            <div class="p-3 text-center text-danger">
                <i class="fas fa-exclamation-triangle fa-lg mb-2"></i>
                <p class="mb-0">Error en la búsqueda</p>
                <small>Intenta nuevamente</small>
            </div>
        `;
        searchResults.style.display = 'block';
    }
    
    function hideSearchResults() {
        searchResults.style.display = 'none';
        currentSearchTerm = '';
    }
    
    function getStatusBadgeClass(status) {
        const classes = {
            'Activo': 'bg-success',
            'pendiente': 'bg-warning',
            'Suspendido': 'bg-danger',
            'Terminado': 'bg-info',
            'Baja': 'bg-dark'
        };
        return classes[status] || 'bg-secondary';
    }
    
    // ========== EVENT LISTENERS ==========
    // Evento principal para el autocomplete (con debounce)
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        
        // Limpiar timeout anterior
        clearTimeout(searchTimeout);
        
        // Si el campo está vacío, ocultar resultados
        if (searchTerm === '') {
            hideSearchResults();
            return;
        }
        
        // Si tiene al menos 2 caracteres, iniciar búsqueda con debounce
        if (searchTerm.length >= 2) {
            searchTimeout = setTimeout(() => {
                performAutoComplete(searchTerm);
            }, 300); // Debounce de 300ms
        } else {
            hideSearchResults();
        }
    });
    
    // Limpiar búsqueda
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        hideSearchResults();
        searchForm.submit();
    });
    
    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            hideSearchResults();
        }
    });
    
    // Navegación con teclado en resultados
    searchInput.addEventListener('keydown', function(event) {
        if (!searchResults.style.display || searchResults.style.display === 'none') {
            return;
        }
        
        const results = searchResults.querySelectorAll('.search-result-item');
        let activeIndex = -1;
        
        // Encontrar elemento activo actual
        results.forEach((result, index) => {
            if (result.classList.contains('active')) {
                activeIndex = index;
            }
        });
        
        // Manejar teclas de navegación
        switch(event.key) {
            case 'ArrowDown':
                event.preventDefault();
                if (results.length === 0) return;
                
                if (activeIndex < results.length - 1) {
                    if (activeIndex >= 0) {
                        results[activeIndex].classList.remove('active');
                    }
                    activeIndex++;
                    results[activeIndex].classList.add('active');
                    results[activeIndex].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                }
                break;
                
            case 'ArrowUp':
                event.preventDefault();
                if (results.length === 0) return;
                
                if (activeIndex > 0) {
                    if (activeIndex >= 0) {
                        results[activeIndex].classList.remove('active');
                    }
                    activeIndex--;
                    results[activeIndex].classList.add('active');
                    results[activeIndex].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                }
                break;
                
            case 'Enter':
                event.preventDefault();
                if (activeIndex >= 0 && results[activeIndex]) {
                    window.location.href = results[activeIndex].getAttribute('href');
                } else if (searchInput.value.trim().length > 0) {
                    // Si no hay elemento activo pero hay texto, enviar formulario
                    searchForm.submit();
                }
                break;
                
            case 'Escape':
                hideSearchResults();
                break;
        }
    });
    
    // Hover en resultados
    searchResults.addEventListener('mouseover', function(event) {
        const item = event.target.closest('.search-result-item');
        if (item) {
            // Remover active de todos los items
            searchResults.querySelectorAll('.search-result-item').forEach(el => {
                el.classList.remove('active');
            });
            // Agregar active al item hover
            item.classList.add('active');
        }
    });
    
    // ========== FUNCIONALIDAD ADICIONAL ==========
    // Filtros automáticos (sin botón de buscar)
    document.getElementById('estatus').addEventListener('change', function() {
        searchForm.submit();
    });
    
    document.getElementById('instituto_id').addEventListener('change', function() {
        searchForm.submit();
    });
    
    document.getElementById('tipo_cliente').addEventListener('change', function() {
        searchForm.submit();
    });
    
    // Precargar resultados si hay un término de búsqueda al cargar la página
    if (searchInput.value.trim().length >= 2) {
        setTimeout(() => {
            performAutoComplete(searchInput.value.trim());
        }, 500);
    }
});
</script>

<style>
/* Estilos adicionales para mejorar el autocomplete */
#searchResults {
    z-index: 1050;
    margin-top: 2px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(0, 0, 0, 0.175);
}

.search-result-item {
    transition: all 0.2s ease;
    border-left: none;
    border-right: none;
}

.search-result-item:hover, .search-result-item.active {
    background-color: #f8f9fa;
    border-left: 3px solid #0d6efd !important;
}

.search-result-item:first-child {
    border-top: none;
}

.search-result-item:last-child {
    border-bottom: none;
}

/* Estilo para el campo de búsqueda cuando hay resultados */
#search:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Scroll personalizado para resultados */
#searchResults::-webkit-scrollbar {
    width: 6px;
}

#searchResults::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#searchResults::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

#searchResults::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endpush
@endsection
