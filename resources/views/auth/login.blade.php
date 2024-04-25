@section('title', 'Login')
<x-guest-layout>
    <div class="container-fluid bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="bg-white p-4 rounded shadow-lg" style="width: 25rem;">
            <a href="/login" target="_blank"><img src="{{ asset('assets/images/logo.png') }}" alt="Larkin Logo"
                    class="mx-auto d-block mb-4 cursor-pointer" style="width: 200px" />
            </a>

            <h3 class="font-weight-bold mb-4 text-dark text-center">HRMS | Login</h3>
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ session('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group mb-4">
                    <label for="email" class="text-sm font-weight-bold text-gray-700 mb-1">Email</label>
                    <input name="email" :value="old('email')" required autofocus type="email" class="form-control"
                        placeholder="email">

                    @error('email')
                        <p class="text-sm text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="text-sm font-weight-bold text-gray-700 mb-1">Password</label>
                    <input name="password" :value="old('password')" required autofocus type="password"
                        class="form-control" placeholder="password">

                    @error('password')
                        <p class="text-sm text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    Login
                </button>

                {{-- <a href="{{ route('forgot-password') }}">Forgot password?</a> --}}
            </form>
        </div>
    </div>
</x-guest-layout>
