<ul class="nav nav-tabs mb-3" id="clienteTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#datos">
            Datos Generales
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#identificadores">
            Identificadores
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contacto">
            Contacto
        </button>
    </li>
</ul>

<div class="tab-content">

    {{-- ================= DATOS GENERALES ================= --}}
    <div class="tab-pane fade show active" id="datos">
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Nombre</label>
                <input type="text" 
                       name="nombre" 
                       class="form-control"
                       value="{{ old('nombre', $cliente->nombre) }}" required>
            </div>

            <div class="col-md-4">
                <label>Apellido Paterno</label>
                <input type="text" 
                       name="apellido_paterno" 
                       class="form-control"
                       value="{{ old('apellido_paterno', $cliente->apellido_paterno) }}">
            </div>

            <div class="col-md-4">
                <label>Apellido Materno</label>
                <input type="text" 
                       name="apellido_materno" 
                       class="form-control"
                       value="{{ old('apellido_materno', $cliente->apellido_materno) }}">
            </div>
        </div>
    </div>

    {{-- ================= IDENTIFICADORES ================= --}}
    <div class="tab-pane fade" id="identificadores">

		@php
			$curps = $cliente->curps ?? collect();
			$curpPrincipal = optional($curps->where('es_principal', true)->first())->curp;
			$curpSecundarios = $curps->where('es_principal', false)->pluck('curp')->values();
		
			$rfcs = $cliente->rfcs ?? collect();
			$rfcPrincipal = optional($rfcs->where('es_principal', true)->first())->rfc;
			$rfcSecundarios = $rfcs->where('es_principal', false)->pluck('rfc')->values();
		
			$nsss = $cliente->nsss ?? collect();
			$nssPrincipal = optional($nsss->where('es_principal', true)->first())->nss;
			$nssSecundarios = $nsss->where('es_principal', false)->pluck('nss')->values();
		@endphp


        <div class="row mb-3">
            <div class="col-md-4">
                <label>CURP Principal</label>
                <input type="text" name="curp" class="form-control"
                       value="{{ old('curp', $curpPrincipal) }}">
            </div>
            <div class="col-md-4">
                <label>CURP 2</label>
                <input type="text" name="curp2" class="form-control"
                       value="{{ old('curp2', $curpSecundarios[0] ?? '') }}">
            </div>
            <div class="col-md-4">
                <label>CURP 3</label>
                <input type="text" name="curp3" class="form-control"
                       value="{{ old('curp3', $curpSecundarios[1] ?? '') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>RFC Principal</label>
                <input type="text" name="rfc" class="form-control"
                       value="{{ old('rfc', $rfcPrincipal) }}">
            </div>
            <div class="col-md-6">
                <label>RFC 2</label>
                <input type="text" name="rfc2" class="form-control"
                       value="{{ old('rfc2', $rfcSecundarios[0] ?? '') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label>NSS Principal</label>
                <input type="text" name="nss" class="form-control"
                       value="{{ old('nss', $nssPrincipal) }}">
            </div>
            <div class="col-md-3">
                <label>NSS 2</label>
                <input type="text" name="nss2" class="form-control"
                       value="{{ old('nss2', $nssSecundarios[0] ?? '') }}">
            </div>
            <div class="col-md-3">
                <label>NSS 3</label>
                <input type="text" name="nss3" class="form-control"
                       value="{{ old('nss3', $nssSecundarios[1] ?? '') }}">
            </div>
            <div class="col-md-3">
                <label>NSS 4</label>
                <input type="text" name="nss4" class="form-control"
                       value="{{ old('nss4', $nssSecundarios[2] ?? '') }}">
            </div>
        </div>

    </div>

    {{-- ================= CONTACTO ================= --}}
    <div class="tab-pane fade" id="contacto">

        @php
            $contactos = $cliente->contactos;
            $celulares = $contactos->where('tipo', 'CELULAR')->pluck('valor')->values();
            $telefonos = $contactos->where('tipo', 'CASA')->pluck('valor')->values();
            $correos = $contactos->where('tipo', 'CORREO')->pluck('valor')->values();
        @endphp

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Celular 1</label>
                <input type="text" name="celular1" class="form-control"
                       value="{{ old('celular1', $celulares[0] ?? '') }}">
            </div>
            <div class="col-md-4">
                <label>Celular 2</label>
                <input type="text" name="celular2" class="form-control"
                       value="{{ old('celular2', $celulares[1] ?? '') }}">
            </div>
            <div class="col-md-4">
                <label>Teléfono Casa</label>
                <input type="text" name="telcasa" class="form-control"
                       value="{{ old('telcasa', $telefonos[0] ?? '') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Correo 1</label>
                <input type="email" name="correo1" class="form-control"
                       value="{{ old('correo1', $correos[0] ?? '') }}">
            </div>
            <div class="col-md-4">
                <label>Correo 2</label>
                <input type="email" name="correo2" class="form-control"
                       value="{{ old('correo2', $correos[1] ?? '') }}">
            </div>
            <div class="col-md-4">
                <label>Correo Personal</label>
                <input type="email" name="correo_personal" class="form-control"
                       value="{{ old('correo_personal', $correos[2] ?? '') }}">
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
document.querySelectorAll('input[type="text"]').forEach(input => {
    input.addEventListener('input', function() {
        if (this.name.includes('curp') || this.name.includes('rfc')) {
            this.value = this.value.toUpperCase();
        }
    });
});
</script>
@endpush
