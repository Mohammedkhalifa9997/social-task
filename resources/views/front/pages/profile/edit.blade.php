@extends('front.layouts.front.app')
@section('title', 'Edit Profile')
@section('content')
    <!-- Main Content -->
    <div class="container my-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                    <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary me-3">
                        <i class="fa fa-arrow-left me-1"></i>Back
                    </a>
                    <h2 class="fw-bold mb-0">Edit Profile</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="fw-bold mb-0">Profile Picture</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ displayImage($user->image) }}" alt="Profile" id="profilePicturePreview"
                                    class="rounded-circle me-4" style="width: 120px; height: 120px; object-fit: cover;">
                                <div>
                                    <input type="file" id="profilePictureInput" name="image" accept="image/*" class="d-none"
                                        onchange="previewProfilePicture(this)">
                                    <button type="button" class="btn btn-primary mb-2"
                                        onclick="document.getElementById('profilePictureInput').click()">
                                        <i class="fa fa-camera me-1"></i>Change Picture
                                    </button>
                                    <p class="text-muted small mb-0">JPG, PNG or GIF. Max size 5MB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-0">
                            <h5 class="fw-bold mb-0">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" id="username" value="{{ old('username', $user->username) }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" name="bio" id="bio" rows="3"
                                    placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3 mb-4">
                        <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary px-4">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa fa-check-lg me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function previewProfilePicture(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profilePicturePreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewCoverPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('coverPhotoPreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush