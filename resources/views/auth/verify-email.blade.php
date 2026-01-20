@extends('front.layouts.auth.app')
@section('title', 'Verify Email')
@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fa fa-envelope text-primary fs-1 mb-3"></i>
                            <h2 class="fw-bold mb-2">Verify Your Email</h2>
                            <p class="text-muted">Thanks for signing up! Before getting started, could you verify your email
                                address by clicking on the link we just emailed to you? If you didn't receive the email, we
                                will gladly send you another.</p>
                        </div>

                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success mb-4">
                                <i class="fa fa-check-circle me-2"></i>
                                A new verification link has been sent to the email address you provided during registration.
                            </div>
                        @endif

                        @if (session('Success'))
                            <div class="alert alert-success mb-4">
                                <i class="fa fa-check-circle me-2"></i>
                                {{ session('Success') }}
                            </div>
                        @endif

                        <div class="d-flex flex-column gap-3">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-paper-plane me-2"></i>Resend Verification Email
                                </button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary w-100">
                                    <i class="fa fa-sign-out-alt me-2"></i>Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection