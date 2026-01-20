@extends('front.layouts.auth.app')
@section('title', 'Register')
@push('css')
    <style>
        #imagePreview {
            width: 120px;
            height: 120px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(
                135deg,
                #1877f2 0%,
                #42b72a 100%
            );
            overflow: hidden;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg border-0 login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fa fa-user-plus text-primary fs-1 mb-3"></i>
                            <h2 class="fw-bold mb-2">Create Account</h2>
                            <p class="text-muted">Join our social community today</p>
                        </div>

                        <form id="registerForm" enctype="multipart/form-data" action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3 text-center">
                                <label for="profileImage" class="form-label d-block">Profile Picture</label>
                                <div class="mb-2 position-relative d-inline-block">
                                    <div id="imagePreview" class="rounded-circle border border-primary">
                                        <i class="fa fa-user text-white" style="font-size: 3rem"></i>
                                    </div>
                                    <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 border border-white"
                                        style="cursor: pointer; transform: translate(25%, 25%)"
                                        onclick="document.getElementById('profileImage').click()">
                                        <i class="fa fa-camera text-white"></i>
                                    </div>
                                </div>
                                <input type="file" name="image" class="form-control d-none" id="profileImage" accept="image/*"
                                    onchange="previewImage(this)" />
                                <div class="mt-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        onclick="document.getElementById('profileImage').click()">
                                        <i class="fa fa-upload me-1"></i>Choose Image
                                    </button>
                                </div>
                                <div class="form-text">
                                    Optional: Upload your profile picture
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa fa-user text-muted"></i>
                                    </span>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control border-start-0" id="name"
                                        placeholder="Enter your full name" required />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control border-start-0" id="email"
                                        placeholder="Enter your email" required />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password" value="{{ old('password') }}" class="form-control border-start-0" id="password"
                                        placeholder="Enter your password" required />
                                    <button class="btn btn-outline-secondary border-start-0" type="button"
                                        id="togglePassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control border-start-0" id="confirmPassword"
                                        placeholder="Confirm your password" required />
                                    <button class="btn btn-outline-secondary border-start-0" type="button"
                                        id="toggleConfirmPassword">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                <div id="passwordMatch" class="form-text text-danger" style="display: none">
                                    <i class="fa fa-exclamation-circle"></i> Passwords do not
                                    match
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 align-items-start pt-3">
                                        <i class="fa fa-info-circle text-muted"></i>
                                    </span>
                                    <textarea name="bio" value="{{ old('bio') }}" class="form-control border-start-0" id="bio" rows="3"
                                        placeholder="Tell us about yourself (optional)"></textarea>
                                </div>
                                <div class="form-text">
                                    Optional: Share a brief description about yourself
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                <i class="fa fa-user-plus me-2"></i>Create Account
                            </button>
                        </form>

                        <hr class="my-4" />

                        <div class="text-center">
                            <p class="text-muted small mb-0">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">Sign In</a>
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
        function previewImage(input) {
            const preview = document.getElementById("imagePreview");
            const file = input.files[0];

            if (file) {
                if (!file.type.startsWith("image/")) {
                    toastr.error("Please select an image file");
                    input.value = "";
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    toastr.error("Image size should be less than 5MB");
                    input.value = "";
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML =
                        '<img src="' +
                        e.target.result +
                        '" style="width: 100%; height: 100%; object-fit: cover;">';
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML =
                    '<i class="fa fa-user text-white" style="font-size: 3rem;"></i>';
                preview.style.background =
                    "linear-gradient(135deg, #1877f2 0%, #42b72a 100%)";
            }
        }

        document
            .getElementById("imagePreview")
            .addEventListener("click", function () {
                document.getElementById("profileImage").click();
            });

        document
            .getElementById("togglePassword")
            .addEventListener("click", function () {
                const password = document.getElementById("password");
                const icon = this.querySelector("i");
                if (password.type === "password") {
                    password.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    password.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            });

        document
            .getElementById("toggleConfirmPassword")
            .addEventListener("click", function () {
                const confirmPassword = document.getElementById("confirmPassword");
                const icon = this.querySelector("i");
                if (confirmPassword.type === "password") {
                    confirmPassword.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    confirmPassword.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            });

        document
            .getElementById("confirmPassword")
            .addEventListener("input", function () {
                const password = document.getElementById("password").value;
                const confirmPassword = this.value;
                const matchMessage = document.getElementById("passwordMatch");

                if (confirmPassword.length > 0) {
                    if (password !== confirmPassword) {
                        matchMessage.style.display = "block";
                        this.classList.add("is-invalid");
                    } else {
                        matchMessage.style.display = "none";
                        this.classList.remove("is-invalid");
                        this.classList.add("is-valid");
                    }
                } else {
                    matchMessage.style.display = "none";
                    this.classList.remove("is-invalid", "is-valid");
                }
            });
    </script>
@endpush