@extends('front.layouts.auth.app')
@section('title', 'Login')
@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg border-0 login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fa fa-users text-primary fs-1 mb-3"></i>
                            <h2 class="fw-bold mb-2">Welcome Back</h2>
                            <p class="text-muted">Sign in to continue to Social App</p>
                        </div>

                        <form id="loginForm" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control border-start-0" id="email"
                                        placeholder="Enter your email" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password"class="form-control border-start-0" id="password"
                                        placeholder="Enter your password" required>
                                    <button class="btn btn-outline-secondary border-start-0" type="button"
                                        id="togglePassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} class="form-check-input" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                <i class="fa fa-sign-in-alt me-2"></i>Sign In
                            </button>

                            <div class="text-center">
                                <a href="{{ route('password.request') }}" class="text-decoration-none text-muted small">Forgot
                                    password?</a>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted small mb-0">Don't have an account?
                                <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold">Sign Up</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
@endpush