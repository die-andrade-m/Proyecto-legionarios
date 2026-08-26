<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="alert alert-success bg-success bg-opacity-10 text-success border-0 small mb-3" :status="session('status')" />

    <h4 class="text-white text-center mb-3">Iniciar Sesión</h4>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label text-secondary small">Correo Electrónico</label>
            <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="text-danger small mt-1 list-unstyled" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="password" class="form-label text-secondary small mb-0">Contraseña</label>
                @if (Route::has('password.request'))
                    <a class="text-link" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
            <input id="password" class="form-control mt-1" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="text-danger small mt-1 list-unstyled" />
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input bg-dark border-secondary border-opacity-25" name="remember">
            <label for="remember_me" class="form-check-label text-secondary small">Mantener sesión iniciada</label>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-premium fs-5">
                Ingresar
            </button>
        </div>

        @if (Route::has('register'))
            <div class="text-center text-secondary small mt-3">
                ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-link">Regístrate aquí</a>
            </div>
        @endif
    </form>
</x-guest-layout>
