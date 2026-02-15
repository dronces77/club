@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container py-5">

    {{-- HEADER PREMIUM --}}
    <div class="profile-header mb-5 p-4 rounded-4 text-white shadow-sm">
        <div class="d-flex align-items-center">
            <div class="me-4">
                <img src="{{ $usuario->foto ? asset('storage/perfiles/'.$usuario->foto) : 'https://via.placeholder.com/150' }}"
                     class="rounded-circle profile-avatar"
                     width="120"
                     height="120">
            </div>
            <div>
                <h3 class="fw-bold mb-1">{{ $usuario->nombre }}</h3>
                <p class="mb-1">{{ $usuario->email }}</p>
                <small>
                    Miembro desde {{ $usuario->created_at ? $usuario->created_at->format('d M Y') : 'N/A' }}
                </small>
            </div>
        </div>
    </div>

    {{-- INFORMACIÓN --}}
    <div class="card border-0 shadow-lg rounded-4 profile-card">
        <div class="card-body p-5">

            <h5 class="fw-bold mb-4">Información Personal</h5>

            {{-- Nombre --}}
            <div class="mb-4">
                <label class="form-label text-muted fw-semibold">Nombre</label>
                <div class="profile-field">
                    <i class="fas fa-user icon-left"></i>
                    <input type="text"
                           class="form-control input-profile"
                           value="{{ $usuario->nombre }}"
                           disabled>
                </div>
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="form-label text-muted fw-semibold">Correo Electrónico</label>
                <div class="profile-field">
                    <i class="fas fa-envelope icon-left"></i>
                    <input type="text"
                           class="form-control input-profile"
                           value="{{ $usuario->email }}"
                           disabled>
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('config.perfil.edit') }}"
                   class="btn btn-primary px-4 py-2 rounded-pill shadow-sm btn-edit">
                    <i class="fas fa-edit me-2"></i> Editar Perfil
                </a>
            </div>

        </div>
    </div>

</div>

{{-- ================= ESTILOS ULTRA PREMIUM ================= --}}
<style>
/* ================= HEADER ================= */
.profile-header {
    background: linear-gradient(135deg, #4f4f96, #765296);
    color: #ffffff;
    border-radius: 20px;
    transition: 0.3s;
}

.profile-header:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(118, 82, 150, 0.35);
}

/* ================= AVATAR ================= */
.profile-avatar {
    object-fit: cover;
    border: 4px solid rgba(255,255,255,0.2);
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
}

/* ================= CARD ================= */
.profile-card {
    border-radius: 20px;
    transition: 0.3s;
}

.profile-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.08);
}

/* ================= INPUTS ================= */
.input-profile {
    padding-left: 40px;
    height: 48px;
    border-radius: 14px;
    background-color: #f8f6fb;
    border: 1px solid #e5dff0;
    font-weight: 500;
    color: #4f4f96;
}

.profile-field {
    position: relative;
}

.icon-left {
    position: absolute;
    top: 14px;
    left: 14px;
    color: #765296;
}

/* ================= BOTÓN ================= */
.btn-edit {
    background: linear-gradient(135deg, #765296, #a4609d);
    border: none;
    color: #ffffff;
    transition: 0.3s;
}

.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(164, 96, 157, 0.4);
}

</style>

@endsection
