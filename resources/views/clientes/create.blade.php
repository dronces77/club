@extends('layouts.app')

@section('title', 'Nuevo Cliente - ClubPension')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus me-2"></i>Nuevo Cliente
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('clientes.store') }}" method="POST">
            @include('clientes._form')
        </form>
    </div>
</div>

<!-- Guía rápida -->
<div class="card mt-4">
    <div class="card-header">
        <i class="fas fa-info-circle me-2"></i> Información importante
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Tipos de Cliente:</h6>
                <ul class="list-unstyled">
                    <li><strong>C:</strong> Cliente activo</li>
                    <li><strong>P:</strong> Prospecto (en proceso)</li>
                    <li><strong>S:</strong> Suspendido temporalmente</li>
                    <li><strong>B:</strong> Baja definitiva</li>
                    <li><strong>I:</strong> Imposible continuar</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Campos obligatorios:</h6>
                <ul class="list-unstyled">
                    <li><span class="text-danger">*</span> Tipo de Cliente</li>
                    <li><span class="text-danger">*</span> Estatus</li>
                    <li><span class="text-danger">*</span> Nombre completo</li>
                    <li><span class="text-danger">*</span> Apellidos</li>
                </ul>
                <p class="text-muted mb-0">
                    <small>El número de cliente se generará automáticamente</small>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualizar régimenes según institución seleccionada
    const institutoSelect = document.getElementById('instituto_id');
    const regimenSelect = document.getElementById('regimen_id');
    const regimenesOriginales = @json($regimenes);
    
    if (institutoSelect && regimenSelect) {
        institutoSelect.addEventListener('change', function() {
            const institutoId = this.value;
            
            // Limpiar opciones actuales excepto la primera
            while (regimenSelect.options.length > 1) {
                regimenSelect.remove(1);
            }
            
            // Agregar régimenes filtrados
            if (institutoId) {
                const regimenesFiltrados = regimenesOriginales.filter(r => r.instituto_id == institutoId);
                
                regimenesFiltrados.forEach(regimen => {
                    const option = document.createElement('option');
                    option.value = regimen.id;
                    option.textContent = `${regimen.nombre}`;
                    regimenSelect.appendChild(option);
                });
                
                regimenSelect.disabled = false;
            } else {
                regimenSelect.disabled = true;
            }
        });
        
        // Disparar evento change al cargar si ya hay un valor
        if (institutoSelect.value) {
            institutoSelect.dispatchEvent(new Event('change'));
        }
    }
    
    // Calcular edad cuando se cambie fecha de nacimiento
    const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
    if (fechaNacimientoInput) {
        fechaNacimientoInput.addEventListener('change', function() {
            if (this.value) {
                const fechaNacimiento = new Date(this.value);
                const hoy = new Date();
                let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                const mes = hoy.getMonth() - fechaNacimiento.getMonth();
                
                if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                    edad--;
                }
                
                // Mostrar edad calculada (puedes agregar un campo oculto si lo necesitas)
                console.log('Edad calculada:', edad);
            }
        });
    }
});
</script>
@endpush
@endsection
