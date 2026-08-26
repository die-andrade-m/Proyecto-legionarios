<x-guest-layout>
    <h4 class="text-white text-center mb-3">Crear Cuenta</h4>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label text-secondary small">Nombre Completo</label>
            <input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="text-danger small mt-1 list-unstyled" />
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label text-secondary small">Correo Electrónico</label>
            <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="text-danger small mt-1 list-unstyled" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label text-secondary small">Contraseña</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="text-danger small mt-1 list-unstyled" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label text-secondary small">Confirmar Contraseña</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger small mt-1 list-unstyled" />
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-premium fs-5">
                Registrarse
            </button>
        </div>

        <div class="text-center text-secondary small mt-3">
            ¿Ya estás registrado? <a href="{{ route('login') }}" class="text-link">Inicia sesión aquí</a>
        </div>
    </form>
</x-guest-layout>
