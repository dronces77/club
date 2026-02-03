@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Total Clientes</h6>
                            <h3 class="card-title">{{ $totalClientes }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Activos</h6>
                            <h3 class="card-title">{{ $clientesActivos }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-user-check fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Dados de Baja</h6>
                            <h3 class="card-title">{{ $clientesBaja }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-user-times fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Este Mes</h6>
                            <h3 class="card-title" id="clientesMesActual">Cargando...</h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-calendar-alt fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Clientes Registrados (Últimos 6 meses)</h5>
                </div>
                <div class="card-body">
                    <canvas id="clientesPorMesChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Institutos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Instituto</th>
                                    <th class="text-end">Clientes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clientesPorInstituto as $instituto)
                                    <tr>
                                        <td>{{ $instituto->nombre }}</td>
                                        <td class="text-end">{{ $instituto->total_clientes ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">No hay datos</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Últimos Clientes Registrados</h5>
                    <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-primary">Ver todos</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Instituto</th>
                                    <th>Régimen</th>
                                    <th>Fecha Contrato</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosClientes as $cliente)
                                    <tr>
                                        <td>{{ $cliente->nombre_completo }}</td>
                                        <td>{{ $cliente->instituto->nombre ?? 'N/A' }}</td>
                                        <td>{{ $cliente->regimen->nombre ?? 'N/A' }}</td>
                                        <td>{{ $cliente->fecha_contrato_formateada }}</td>
                                        <td>
                                            @if($cliente->fecha_baja)
                                                <span class="badge bg-warning">Baja</span>
                                            @else
                                                <span class="badge bg-success">Activo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('clientes.show', $cliente->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No hay clientes registrados</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($cumpleanios && $cumpleanios->count() > 0)
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Cumpleaños este Mes</h5>
                    <span class="badge bg-primary">{{ $cumpleanios->count() }} clientes</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Fecha Cumpleaños</th>
                                    <th>Edad</th>
                                    <th>Contacto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cumpleanios as $cliente)
                                    <tr>
                                        <td>{{ $cliente->nombre_completo }}</td>
                                        <td>
                                            {{ $cliente->fecha_nacimiento ? Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('d/m') : 'N/A' }}
                                            @if($cliente->fecha_nacimiento)
                                                ({{ $cliente->dia_cumpleanios ?? Carbon\Carbon::parse($cliente->fecha_nacimiento)->day }})
                                            @endif
                                        </td>
                                        <td>{{ $cliente->edad ?? 'N/A' }}</td>
                                        <td>
                                            @if($cliente->contactoPrincipal)
                                                {{ $cliente->contactoPrincipal->telefono ?? $cliente->contactoPrincipal->celular ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($contratosPorVencer && $contratosPorVencer->count() > 0)
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Contratos Próximos a Vencer (30 días)</h5>
                    <span class="badge bg-warning">{{ $contratosPorVencer->count() }} clientes</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Instituto</th>
                                    <th>Fecha Contrato</th>
                                    <th>Días Restantes</th>
                                    <th>Contacto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contratosPorVencer as $cliente)
                                    <tr>
                                        <td>{{ $cliente->nombre_completo }}</td>
                                        <td>{{ $cliente->instituto->nombre ?? 'N/A' }}</td>
                                        <td>{{ $cliente->fecha_contrato_formateada }}</td>
                                        <td>
                                            @if(isset($cliente->dias_restantes))
                                                @if($cliente->dias_restantes < 0)
                                                    <span class="badge bg-danger">Vencido: {{ abs($cliente->dias_restantes) }} días</span>
                                                @elseif($cliente->dias_restantes < 7)
                                                    <span class="badge bg-warning">{{ $cliente->dias_restantes }} días</span>
                                                @else
                                                    <span class="badge bg-success">{{ $cliente->dias_restantes }} días</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($cliente->contactoPrincipal)
                                                {{ $cliente->contactoPrincipal->telefono ?? $cliente->contactoPrincipal->celular ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Todos los Clientes</h5>
                    <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-success">Nuevo Cliente</a>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard') }}" class="mb-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Buscar por nombre..." 
                                       value="{{ $search }}">
                            </div>
                            <div class="col-md-3">
                                <select name="instituto_id" class="form-control">
                                    <option value="">Todos los institutos</option>
                                    @foreach($institutos as $instituto)
                                        <option value="{{ $instituto->id }}" {{ $institutoId == $instituto->id ? 'selected' : '' }}>
                                            {{ $instituto->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="regimen_id" class="form-control">
                                    <option value="">Todos los regímenes</option>
                                    @foreach($regimenes as $regimen)
                                        <option value="{{ $regimen->id }}" {{ $regimenId == $regimen->id ? 'selected' : '' }}>
                                            {{ $regimen->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Instituto</th>
                                    <th>Régimen</th>
                                    <th>Trámite</th>
                                    <th>Modalidad</th>
                                    <th>Fecha Contrato</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clientes as $cliente)
                                    <tr>
                                        <td>{{ $cliente->id }}</td>
                                        <td>{{ $cliente->nombre_completo }}</td>
                                        <td>{{ $cliente->instituto->nombre ?? 'N/A' }}</td>
                                        <td>{{ $cliente->regimen->nombre ?? 'N/A' }}</td>
                                        <td>{{ $cliente->tramite->nombre ?? 'N/A' }}</td>
                                        <td>{{ $cliente->modalidad->nombre ?? 'N/A' }}</td>
                                        <td>{{ $cliente->fecha_contrato_formateada }}</td>
                                        <td>
                                            @if($cliente->fecha_baja)
                                                <span class="badge bg-warning">Baja</span>
                                            @else
                                                <span class="badge bg-success">Activo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('clientes.show', $cliente->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No hay clientes registrados</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $clientes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarEstadisticasRapidas();
    inicializarGraficoClientesPorMes();
    setInterval(cargarEstadisticasRapidas, 300000);
});

function cargarEstadisticasRapidas() {
    fetch('{{ route("dashboard.estadisticas") }}?estadisticas_rapidas=1')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.data.estadisticas_rapidas;
                document.getElementById('clientesMesActual').textContent = stats.clientes_mes_actual || 0;
            }
        })
        .catch(error => {
            console.error('Error al cargar estadísticas:', error);
        });
}

function inicializarGraficoClientesPorMes() {
    const ctx = document.getElementById('clientesPorMesChart').getContext('2d');
    
    const data = {
        labels: @json($clientesPorMes['meses']),
        datasets: [{
            label: 'Clientes Registrados',
            data: @json($clientesPorMes['clientes']),
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    };
    
    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    };
    
    new Chart(ctx, config);
}
</script>
@endpush

@push('styles')
<style>
    .card {
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .card-title {
        color: #333;
    }
    
    .card-subtitle {
        font-size: 0.85rem;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #666;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
</style>
@endpush