@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-person-plus"></i> Nuevo Prospecto</h4>
        <a href="{{ route('prospectos.index') }}" class="btn btn-secondary">
            ← Volver a Prospectos
        </a>
    </div>

    <form method="POST" action="{{ route('prospectos.store') }}" id="prospectoForm">
        @csrf

        {{-- ================= DATOS PERSONALES ================= --}}
        <div class="card mb-4">
            <div class="card-header text-primary">
                <i class="bi bi-person"></i> Datos Personales
            </div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre"
                        class="form-control @error('nombre') is-invalid @enderror"
                        value="{{ old('nombre') }}" required>
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                    <input type="text" name="apellido_paterno"
                        class="form-control @error('apellido_paterno') is-invalid @enderror"
                        value="{{ old('apellido_paterno') }}" required>
                    @error('apellido_paterno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido Materno <span class="text-danger">*</span></label>
                    <input type="text" name="apellido_materno"
                        class="form-control @error('apellido_materno') is-invalid @enderror"
                        value="{{ old('apellido_materno') }}" required>
                    @error('apellido_materno')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- ================= IDENTIFICACIÓN Y CONTACTO ================= --}}
        <div class="card mb-4">
            <div class="card-header text-primary">
                <i class="bi bi-card-text"></i> Identificación y Contacto
            </div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">CURP <span class="text-danger">*</span></label>
                    <input type="text"
						name="curp"
						maxlength="18"
						minlength="18"
						placeholder="18 dígitos"
                        class="form-control text-uppercase @error('curp') is-invalid @enderror"
                        value="{{ old('curp') }}" required>
                    <small class="text-muted">Debe contener 18 caracteres</small>
                    @error('curp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">NSS <span class="text-danger">*</span></label>
                    <input type="text"
                        name="nss"
                        maxlength="11"
                        minlength="11"
                        placeholder="11 dígitos"
                        class="form-control @error('nss') is-invalid @enderror"
                        value="{{ old('nss') }}"
                        required>
                    <small class="text-muted">Número de Seguridad Social (11 dígitos)</small>
                    @error('nss')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Celular Principal <span class="text-danger">*</span></label>
                    <input type="text" name="celular" maxlength="13" minlength="10"
                        class="form-control @error('celular') is-invalid @enderror"
                        value="{{ old('celular') }}" required>
                    <small class="text-muted">Debe contener minimo 10 caracteres y maximo 13</small>
                    @error('celular')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

<div class="mb-3">
    <label for="notas" class="form-label">Notas</label>
    <textarea name="notas" id="notas" class="form-control" maxlength="250">{{ old('notas') }}</textarea>
</div>

            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Crear Prospecto
            </button>
        </div>
    </form>
</div>


{{-- ===== INCLUIR SELECT2 ===== --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- ===== JS CON SELECT2 ===== --}}
<script>
    // Inicializar Select2 cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Select2 para el campo de cliente
        $('#cliente_origen_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Escriba para buscar...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "No se encontraron resultados";
                },
                searching: function() {
                    return "Buscando...";
                },
                inputTooShort: function(args) {
                    return "Ingrese al menos " + args.minimum + " caracteres";
                }
            },
            minimumInputLength: 2,
            sorter: function(data) {
                return data.sort(function(a, b) {
                    return a.text.localeCompare(b.text);
                });
            }
        });

        // Mostrar/ocultar el campo de cliente según la selección
        const origenSelect = document.getElementById('origen');
        const clienteContainer = document.getElementById('cliente_origen_container');
        const clienteSelect = $('#cliente_origen_id');
        
        origenSelect.addEventListener('change', function() {
            if (this.value === 'cliente') {
                clienteContainer.classList.remove('d-none');
                clienteSelect.prop('required', true);
                // Re-inicializar Select2 cuando se hace visible
                setTimeout(() => {
                    clienteSelect.select2({
                        theme: 'bootstrap-5',
                        placeholder: 'Escriba para buscar...',
                        allowClear: true,
                        width: '100%',
                        minimumInputLength: 2
                    });
                }, 100);
            } else {
                clienteContainer.classList.add('d-none');
                clienteSelect.prop('required', false);
                clienteSelect.val(null).trigger('change');
            }
        });

        // Validación del formulario
        document.getElementById('prospectoForm').addEventListener('submit', function(e) {
            const origen = document.getElementById('origen');
            const clienteSelectElement = document.getElementById('cliente_origen_id');
            const errorMsg = document.getElementById('cliente-error');
            
            if (origen.value === 'cliente' && !clienteSelectElement.value) {
                e.preventDefault();
                clienteSelectElement.classList.add('is-invalid');
                errorMsg.classList.remove('d-none');
                // Enfocar el campo
                clienteSelectElement.focus();
            } else {
                clienteSelectElement.classList.remove('is-invalid');
                errorMsg.classList.add('d-none');
            }
        });

        // Limpiar validación cuando se selecciona un cliente
        $('#cliente_origen_id').on('select2:select', function() {
            this.classList.remove('is-invalid');
            document.getElementById('cliente-error').classList.add('d-none');
        });

        // Si hay valor antiguo, mostrar el campo
        @if(old('origen') === 'cliente')
            document.getElementById('origen').value = 'cliente';
            document.getElementById('cliente_origen_container').classList.remove('d-none');
        @endif
    });
</script>

{{-- Estilos adicionales --}}
<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
    }
    
    .select2-container--bootstrap-5 .select2-selection--single {
        padding: .375rem .75rem .375rem .75rem;
    }
    
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding-left: 0;
    }
    
    .select2-container--bootstrap-5 .select2-dropdown {
        border-color: #dee2e6;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .select2-search--dropdown {
        padding: 0.5rem;
    }
    
    .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
    }
    
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
@endsection
