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

    <!-- 🔍 FILTROS DE BÚSQUEDA -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros de Búsqueda</h6>
        </div>
        <div class="card-body">
            <form id="searchForm" class="row g-3">
                <div class="col-md-4 position-relative">
                    <label for="searchInput" class="form-label"><i class="fas fa-search me-1"></i>Buscar cliente</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="No. Cliente, Nombre, Apellidos, CURP o NSS" autocomplete="off" value="">
                </div>

                <div class="col-md-3">
                    <label for="institutoFilter" class="form-label"><i class="fas fa-building me-1"></i>Institución</label>
                    <select class="form-select" id="institutoFilter">
                        <option value="todos">Todas las instituciones</option>
                        @foreach($institutos as $instituto)
                            <option value="{{ strtolower($instituto->codigo) }}">{{ $instituto->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="estatusFilter" class="form-label"><i class="fas fa-flag me-1"></i>Estatus</label>
                    <select class="form-select" id="estatusFilter">
                        <option value="todos">Todos los estatus</option>
                        <option value="activo">Activo</option>
                        <option value="suspendido">Suspendido</option>
                        <option value="terminado">Terminado</option>
                        <option value="baja">Baja</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                        <i class="fas fa-redo me-1"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 📋 TABLA DE CLIENTES -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i> Lista de Clientes
                <span class="badge bg-secondary ms-2">{{ $clientes->total() }} registros</span>
            </h5>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
            @include('partials.alerts')

            <div class="table-responsive">
                <table class="table table-hover table-striped" id="clientesTable">
                    <thead>
                        <tr>
                            <th data-column="0" class="sortable">No. Cliente <i class="fas fa-sort ms-1"></i></th>
                            <th data-column="1" class="sortable">Nombre <i class="fas fa-sort ms-1"></i></th>
                            <th data-column="2" class="sortable">CURP <i class="fas fa-sort ms-1"></i></th>
                            <th data-column="3" class="sortable">NSS <i class="fas fa-sort ms-1"></i></th>
                            <th data-column="4" class="sortable">Institución <i class="fas fa-sort ms-1"></i></th>
                            <th data-column="5" class="sortable">Estatus <i class="fas fa-sort ms-1"></i></th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                        <tr>
                            <td><strong>{{ $cliente->no_cliente ?? 'N/A' }}</strong></td>
                            <td>{{ $cliente->nombre }} {{ $cliente->apellido_paterno ?? '-' }} {{ $cliente->apellido_materno ?? '-' }}</td>
                            <td>
                                @if($cliente->curps->count() > 0)
                                    @php $curpPrincipal = $cliente->curps->where('es_principal', true)->first(); @endphp
                                    {{ $curpPrincipal->curp ?? $cliente->curps->first()->curp }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($cliente->nss->count() > 0)
                                    @php $nssPrincipal = $cliente->nss->where('es_principal', true)->first(); @endphp
                                    {{ $nssPrincipal->nss ?? $cliente->nss->first()->nss }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    @if($cliente->instituto && $cliente->instituto->codigo !== 'INA')
                                        <span class="badge bg-success" data-codigo="{{ $cliente->instituto->codigo }}">
                                            {{ $cliente->instituto->nombre }}
                                        </span>
                                    @endif
                                    @if($cliente->instituto2 && $cliente->instituto2->codigo !== 'INA')
                                        <span class="badge bg-primary" data-codigo="{{ $cliente->instituto2->codigo }}">
                                            {{ $cliente->instituto2->nombre }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                @php
                                    $nombreEstatus = $cliente->estatusCliente->nombre ?? null;
                                    $estatusClass = 'bg-secondary';
                                    if ($nombreEstatus === 'Activo') $estatusClass = 'bg-primary';
                                    elseif ($nombreEstatus === 'Suspendido') $estatusClass = 'bg-warning text-dark';
                                    elseif ($nombreEstatus === 'Baja') $estatusClass = 'bg-danger';
                                    elseif ($nombreEstatus === 'Terminado') $estatusClass = 'bg-success';
                                @endphp
                                <span class="badge {{ $estatusClass }}">{{ $nombreEstatus ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-outline-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-outline-warning" title="Editar" @if($cliente->tipo_cliente !== 'C') disabled @endif>
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Eliminar">
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
                        @if($clientes->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">« Anterior</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $clientes->previousPageUrl() }}" rel="prev">« Anterior</a></li>
                        @endif

                        @foreach($clientes->getUrlRange(max(1, $clientes->currentPage() - 2), min($clientes->lastPage(), $clientes->currentPage() + 2)) as $page => $url)
                            @if($page == $clientes->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        @if($clientes->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $clientes->nextPageUrl() }}" rel="next">Siguiente »</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">Siguiente »</span></li>
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
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Información</h6>
                </div>
                <div class="card-body">
                    <p><strong>Vista de Clientes:</strong> Esta página muestra <strong>solo registros con tipo "Cliente"</strong>.</p>
                    <p>Para ver prospectos, imposibles, bajas y suspendidos, visita la vista de <strong>Prospectos</strong>.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-search me-2"></i> Ayuda de Búsqueda</h6>
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
    const searchInput = document.getElementById('searchInput');
    const estatusFilter = document.getElementById('estatusFilter');
    const institutoFilter = document.getElementById('institutoFilter');
    const clearBtn = document.getElementById('clearFilters');
    const table = document.getElementById('clientesTable').tBodies[0];

    // -----------------------------
    // FILTRADO POR INPUTS
    // -----------------------------
    function filtrarTabla() {
        const term = searchInput.value.toLowerCase();
        const estatus = estatusFilter.value.toLowerCase();
        const instituto = institutoFilter.value.toLowerCase();

        Array.from(table.rows).forEach(row => {
            const nombre = row.cells[1].textContent.toLowerCase();
            const curp = row.cells[2].textContent.toLowerCase();
            const nss = row.cells[3].textContent.toLowerCase();
            const estatusRow = row.cells[5].textContent.toLowerCase();

            const instituciones = Array.from(row.cells[4].querySelectorAll('span'))
                .map(s => s.dataset.codigo.toLowerCase());

            let mostrar = true;

            if (term && !nombre.includes(term) && !curp.includes(term) && !nss.includes(term)) mostrar = false;
            if (estatus !== 'todos' && !estatusRow.includes(estatus)) mostrar = false;
            if (instituto !== 'todos' && !instituciones.includes(instituto)) mostrar = false;

            row.style.display = mostrar ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filtrarTabla);
    estatusFilter.addEventListener('change', filtrarTabla);
    institutoFilter.addEventListener('change', filtrarTabla);

    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        estatusFilter.value = 'todos';
        institutoFilter.value = 'todos';
        filtrarTabla();
    });

    filtrarTabla(); // Filtra al cargar la página

    // -----------------------------
    // ORDENAMIENTO POR COLUMNAS
    // -----------------------------
    const getCellValue = (row, index) => {
        const cell = row.cells[index];
        if(index === 4) {
            const span = cell.querySelector('span');
            return span ? span.textContent.trim() : '';
        }
        return cell.textContent.trim();
    };

    const tableHeaders = document.querySelectorAll('#clientesTable th.sortable');
    tableHeaders.forEach(th => {
        th.addEventListener('click', function() {
            const tbody = table;
            const columnIndex = parseInt(th.dataset.column);
            const currentIcon = th.querySelector('i');
            let asc = !th.classList.contains('asc');

            tableHeaders.forEach(h => {
                h.classList.remove('asc', 'desc');
                h.querySelector('i').className = 'fas fa-sort ms-1';
            });

            th.classList.toggle('asc', asc);
            th.classList.toggle('desc', !asc);
            currentIcon.className = asc ? 'fas fa-sort-up ms-1' : 'fas fa-sort-down ms-1';

            const rowsArray = Array.from(tbody.rows);
            rowsArray.sort((a, b) => {
                const aText = getCellValue(a, columnIndex).toLowerCase();
                const bText = getCellValue(b, columnIndex).toLowerCase();

                if(!isNaN(aText) && !isNaN(bText)) return asc ? aText - bText : bText - aText;
                return asc ? aText.localeCompare(bText) : bText.localeCompare(aText);
            });

            rowsArray.forEach(row => tbody.appendChild(row));
        });
    });
});
</script>
@endpush
