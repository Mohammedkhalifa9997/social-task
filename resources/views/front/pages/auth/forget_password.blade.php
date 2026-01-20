@extends('front.layouts.auth.app')
@section('title', 'Forget Password')
@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg border-0 login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fa fa-key text-primary fs-1 mb-3"></i>
                            <h2 class="fw-bold mb-2">Forgot Password?</h2>
                            <p class="text-muted">No worries! Enter your email and we'll send you reset instructions.</p>
                        </div>

                        <form id="forgotPasswordForm" action="{{ route('password.email') }}" method="POST">
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
                                <div class="form-text">We'll send a password reset link to this email</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                <i class="fa fa-paper-plane me-2"></i>Send Reset Link
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