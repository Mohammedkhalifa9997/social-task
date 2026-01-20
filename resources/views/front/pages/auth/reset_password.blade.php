@extends('front.layouts.auth.app')
@section('title', 'Reset Password')
@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg border-0 login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fa fa-lock text-primary fs-1 mb-3"></i>
                            <h2 class="fw-bold mb-2">Reset Password</h2>
                            <p class="text-muted">Enter your new password below</p>
                        </div>

                        <form id="resetPasswordForm" action="{{ route('password.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="hidden" name="token" value="{{ $request->token }}">
                                <input type="hidden" name="email" value="{{ $request->email }}">
                                <label for="password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password" value="{{ old('password') }}" class="form-control border-start-0" id="password"
                                        placeholder="Enter new password" required>
                                    <button class="btn btn-outline-secondary border-start-0" type="button"
                                        id="togglePassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Password must be at least 8 characters long</div>
                            </div>

                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control border-start-0" id="confirmPassword"
                                        placeholder="Confirm new password" required>
                                    <button class="btn btn-outline-secondary border-start-0" type="button"
                                        id="toggleConfirmPassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                <div id="passwordMatch" class="form-text text-danger" style="display: none;">
                                    <i class="fa fa-exclamation-circle"></i> Passwords do not match
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                <i class="fa fa-check me-2"></i>Reset Password
                            </button>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted small mb-0">
                                <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">
                                    <i class="fa fa-arrow-left me-1"></i>Back to Login
                                </a>
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
        document.getElementById('togglePassword').addEventListener('click', function () {
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

        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const confirmPassword = document.getElementById('confirmPassword');
            const icon = this.querySelector('i');
            if (confirmPassword.type === 'password') {
                confirmPassword.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                confirmPassword.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        document.getElementById('confirmPassword').addEventListener('input', function () {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const matchMessage = document.getElementById('passwordMatch');

            if (confirmPassword.length > 0) {
                if (password !== confirmPassword) {
                    matchMessage.style.display = 'block';
                    this.classList.add('is-invalid');
                } else {
                    matchMessage.style.display = 'none';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            } else {
                matchMessage.style.display = 'none';
                this.classList.remove('is-invalid', 'is-valid');
            }
        });

        document.getElementById('password').addEventListener('input', function () {
            const password = this.value;
            if (password.length > 0 && password.length < 8) {
                this.classList.add('is-invalid');
            } else if (password.length >= 8) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    </script>
@endpush