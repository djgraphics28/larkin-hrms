<div class="container-fluid bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="bg-white p-4 rounded shadow-lg" style="width: 25rem;">
        <a href="/login" target="_blank"><img src="{{ asset('assets/images/logo.png') }}" alt="Larkin Logo"
                class="mx-auto d-block mb-4 cursor-pointer" style="width: 200px" />
        </a>

        <h3 class="font-weight-bold mb-4 text-dark text-center">HRMS | Admin Login</h3>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <form>
            <div class="form-group mb-4">
                <label for="email" class="text-sm font-weight-bold text-gray-700 mb-1">Email</label>
                <input wire:model="email" type="email" class="form-control" placeholder="email">

                @error('email')
                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="password" class="text-sm font-weight-bold text-gray-700 mb-1">Password</label>
                <input wire:model="password" type="password" class="form-control" placeholder="password">

                @error('password')
                    <p class="text-sm text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            @error('login_failed')
                <p class="text-sm text-danger mt-1">{{ $message }}</p>
            @enderror

            <button type="button" wire:click="login" class="btn btn-primary w-100">
                Login
            </button>

            <a href="{{ route('forgot-password') }}">Forgot password?</a>
        </form>
    </div>
</div>
