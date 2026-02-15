@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="container py-5">

    <h2 class="club-title mb-4">Editar Perfil</h2>

    <div class="row">

        {{-- PERFIL LATERAL --}}
        <div class="col-lg-4 mb-4">
            <div class="card club-card">
                <div class="card-body text-center">

                    <img id="preview"
                        src="{{ $usuario->foto ? asset('storage/perfiles/'.$usuario->foto) : 'https://via.placeholder.com/150' }}"
                        class="rounded-circle mb-3"
                        width="150"
                        height="150"
                        style="object-fit: cover; border:4px solid #765296;">

                    <h5 class="club-subtitle mb-1">{{ $usuario->nombre }}</h5>
                    <p class="text-muted mb-2">{{ $usuario->email }}</p>

                    <hr>

                    <p class="mb-0">
                        <strong>Miembro desde:</strong><br>
                        {{ $usuario->created_at ? $usuario->created_at->format('d M Y') : 'N/A' }}
                    </p>

                </div>
            </div>
        </div>

        {{-- FORMULARIO --}}
        <div class="col-lg-8">
            <div class="card club-card">
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('config.perfil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- NOMBRE --}}
                        <div class="mb-3">
                            <label class="form-label club-subtitle">Nombre</label>
                            <input type="text"
                                class="form-control club-input"
                                name="nombre"
                                value="{{ old('nombre', $usuario->nombre) }}"
                                required>
                        </div>

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label club-subtitle">Correo Electrónico</label>
                            <input type="email"
                                class="form-control club-input"
                                name="email"
                                value="{{ old('email', $usuario->email) }}"
                                required>
                        </div>

                        {{-- CONTRASEÑAS --}}
                        <div class="mb-4">
                            <label class="form-label club-subtitle">Contraseña Actual</label>
                            <div class="password-wrapper">
                                <i class="fas fa-lock icon-left"></i>
                                <input type="password"
                                    class="form-control club-input input-password"
                                    id="password_actual"
                                    name="password_actual">
                                <i class="fas fa-eye icon-right"
                                    onclick="togglePassword(this, 'password_actual')"></i>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label club-subtitle">Nueva Contraseña</label>
                            <div class="password-wrapper">
                                <i class="fas fa-lock icon-left"></i>
                                <input type="password"
                                    class="form-control club-input input-password"
                                    id="password_nueva"
                                    name="password"
                                    onkeyup="checkStrength(this.value)">
                                <i class="fas fa-eye icon-right"
                                    onclick="togglePassword(this, 'password_nueva')"></i>
                            </div>

                            <div class="strength-meter mt-2">
                                <div id="strength-bar"></div>
                            </div>
                            <small id="strength-text"></small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label club-subtitle">Confirmar Contraseña</label>
                            <div class="password-wrapper">
                                <i class="fas fa-lock icon-left"></i>
                                <input type="password"
                                    class="form-control club-input input-password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    onkeyup="checkMatch()">
                                <i class="fas fa-eye icon-right"
                                    onclick="togglePassword(this, 'password_confirmation')"></i>
                            </div>
                            <small id="match-text"></small>
                        </div>

                        {{-- FOTO --}}
                        <div class="mb-4">
                            <label class="form-label club-subtitle">Foto de Perfil</label>
                            <input type="file" name="foto" class="form-control club-input">
                        </div>

                        {{-- BOTONES --}}
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('config.perfil.show') }}" class="btn btn-club-secondary me-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-club-primary">
                                Guardar Cambios
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<style>
.password-wrapper {
    position: relative;
}

.input-password {
    padding-left: 40px;
    padding-right: 40px;
    height: 45px;
    border-radius: 12px;
    transition: 0.3s;
}

.input-password:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.15rem rgba(13,110,253,.25);
}

.icon-left {
    position: absolute;
    top: 12px;
    left: 12px;
    color: #6c757d;
}

.icon-right {
    position: absolute;
    top: 12px;
    right: 12px;
    cursor: pointer;
    color: #6c757d;
    transition: 0.3s;
}

.icon-right:hover {
    color: #0d6efd;
}

/* Barra de seguridad */
.strength-meter {
    height: 6px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

#strength-bar {
    height: 100%;
    width: 0%;
    transition: 0.3s;
}
</style>


{{-- PREVIEW AUTOMÁTICO --}}

<script>
document.querySelector('input[name="foto"]').addEventListener('change', function(e) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('preview').src = e.target.result;
    }
    reader.readAsDataURL(this.files[0]);
});
</script>
<script>
function togglePassword(icon, inputId) {
    const input = document.getElementById(inputId);

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

/* ===== Fuerza de contraseña ===== */
function checkStrength(password) {
    const bar = document.getElementById("strength-bar");
    const text = document.getElementById("strength-text");

    let strength = 0;

    if (password.length >= 8) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^A-Za-z0-9]/)) strength++;

    switch (strength) {
        case 0:
            bar.style.width = "0%";
            text.innerHTML = "";
            break;
        case 1:
            bar.style.width = "25%";
            bar.style.background = "red";
            text.innerHTML = "Seguridad: Débil";
            text.className = "text-danger";
            break;
        case 2:
            bar.style.width = "50%";
            bar.style.background = "orange";
            text.innerHTML = "Seguridad: Media";
            text.className = "text-warning";
            break;
        case 3:
            bar.style.width = "75%";
            bar.style.background = "#0d6efd";
            text.innerHTML = "Seguridad: Buena";
            text.className = "text-primary";
            break;
        case 4:
            bar.style.width = "100%";
            bar.style.background = "green";
            text.innerHTML = "Seguridad: Fuerte";
            text.className = "text-success";
            break;
    }
}

/* ===== Coincidencia de contraseñas ===== */
function checkMatch() {
    const pass = document.getElementById("password_nueva").value;
    const confirm = document.getElementById("password_confirmation").value;
    const text = document.getElementById("match-text");

    if (confirm.length === 0) {
        text.innerHTML = "";
        return;
    }

    if (pass === confirm) {
        text.innerHTML = "✔ Las contraseñas coinciden";
        text.className = "text-success";
    } else {
        text.innerHTML = "✖ Las contraseñas no coinciden";
        text.className = "text-danger";
    }
}
</script>


@endsection
