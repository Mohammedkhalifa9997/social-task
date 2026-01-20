@extends('front.layouts.front.app')
@section('title', 'Profile')
@push('css')
    <style>
        .post-gallery {
            transition: all 0.3s ease;
        }

        .gallery-overlay {
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .post-gallery:hover .gallery-overlay {
            background: rgba(0, 0, 0, 0.2) !important;
        }

        .post-gallery:hover .gallery-overlay div {
            opacity: 1 !important;
        }

        .gallery-badge {
            transition: all 0.3s ease;
        }

        .gallery-badge:hover {
            transform: scale(1.05);
            background-color: rgba(0, 0, 0, 1) !important;
        }
    </style>
@endpush
@section('content')
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="cover-photo" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 300px;">
        </div>
        <div class="container">
            <div class="profile-info">
                <div class="row align-items-end">
                    <div class="col-md-3 text-center text-md-start">
                        <img src="{{ displayImage(auth()->user()->image) }}" alt="Profile"
                            class="profile-avatar rounded-circle border border-white shadow" />
                    </div>
                    <div class="col-md-9 mt-3 mt-md-0">
                        <h2 class="fw-bold mb-1" id="profileName">{{ auth()->user()->name }}</h2>
                        <p class="text-muted mb-2" id="profileUsername">{{"@" . auth()->user()->username}}</p>
                        <p class="mb-3" id="profileBio">
                            {{ auth()->user()->bio }}
                        </p>
                        <div class="d-flex flex-wrap gap-3 mb-3">
                            <div>
                                <i class="fa fa-calendar me-1"></i>
                                <span>Joined {{ auth()->user()->created_at->format('F Y') }}</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <i class="fa fa-pencil me-1"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Stats -->
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card shadow-sm border-0 text-center">
                    <div class="card-body">
                        <h3 class="fw-bold text-primary mb-0" id="profilePostsCount">
                            {{ $postsCount }}
                        </h3>
                        <p class="text-muted mb-0">Posts</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card shadow-sm border-0 text-center">
                    <div class="card-body">
                        <h3 class="fw-bold text-primary mb-0" id="profileFriendsCount">
                            {{ $friendsCount }}
                        </h3>
                        <p class="text-muted mb-0">Friends</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Tabs -->
        <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button"
                    role="tab">
                    <i class="fa fa-grid me-1"></i>Posts
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="friends-tab" data-bs-toggle="tab" data-bs-target="#friends" type="button"
                    role="tab">
                    <i class="fa fa-people me-1"></i>Friends
                </button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabContent">
            <!-- Posts Tab -->
            <div class="tab-pane fade show active" id="posts" role="tabpanel">
                <div class="row" id="profilePostsContainer">
                    @include('front.pages.profile.partials.posts', ['posts' => $posts])
                </div>
                <div id="postsPagination">
                    @include('front.pages.profile.partials.pagination', ['posts' => $posts])
                </div>
            </div>

            <!-- Friends Tab -->
            <div class="tab-pane fade" id="friends" role="tabpanel">
                <div class="row" id="friendsContainer">
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 text-center">
                            <div class="card-body">
                                <img src="https://placehold.co/80x80" alt="Friend" class="rounded-circle mb-3"
                                    style="width: 80px; height: 80px" />
                                <h6 class="mb-1">Jane Smith</h6>
                                <p class="text-muted small mb-2">@janesmith</p>
                                <p class="text-muted small mb-3">12 mutual friends</p>
                                <button class="btn btn-primary btn-sm w-100">
                                    View Profile
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 text-center">
                            <div class="card-body">
                                <img src="https://placehold.co/80x80" alt="Friend" class="rounded-circle mb-3"
                                    style="width: 80px; height: 80px" />
                                <h6 class="mb-1">Mike Johnson</h6>
                                <p class="text-muted small mb-2">@mikej</p>
                                <p class="text-muted small mb-3">8 mutual friends</p>
                                <button class="btn btn-primary btn-sm w-100">
                                    View Profile
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 text-center">
                            <div class="card-body">
                                <img src="https://placehold.co/80x80" alt="Friend" class="rounded-circle mb-3"
                                    style="width: 80px; height: 80px" />
                                <h6 class="mb-1">Sarah Williams</h6>
                                <p class="text-muted small mb-2">@sarahw</p>
                                <p class="text-muted small mb-3">15 mutual friends</p>
                                <button class="btn btn-primary btn-sm w-100">
                                    View Profile
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 text-center">
                            <div class="card-body">
                                <img src="https://placehold.co/80x80" alt="Friend" class="rounded-circle mb-3"
                                    style="width: 80px; height: 80px" />
                                <h6 class="mb-1">David Brown</h6>
                                <p class="text-muted small mb-2">@davidb</p>
                                <p class="text-muted small mb-3">5 mutual friends</p>
                                <button class="btn btn-primary btn-sm w-100">
                                    View Profile
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 text-center">
                            <div class="card-body">
                                <img src="https://placehold.co/80x80" alt="Friend" class="rounded-circle mb-3"
                                    style="width: 80px; height: 80px" />
                                <h6 class="mb-1">Emily Davis</h6>
                                <p class="text-muted small mb-2">@emilyd</p>
                                <p class="text-muted small mb-3">20 mutual friends</p>
                                <button class="btn btn-primary btn-sm w-100">
                                    View Profile
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 text-center">
                            <div class="card-body">
                                <img src="https://placehold.co/80x80" alt="Friend" class="rounded-circle mb-3"
                                    style="width: 80px; height: 80px" />
                                <h6 class="mb-1">Chris Wilson</h6>
                                <p class="text-muted small mb-2">@chrisw</p>
                                <p class="text-muted small mb-3">7 mutual friends</p>
                                <button class="btn btn-primary btn-sm w-100">
                                    View Profile
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 text-center">
                            <div class="card-body">
                                <img src="https://placehold.co/80x80" alt="Friend" class="rounded-circle mb-3"
                                    style="width: 80px; height: 80px" />
                                <h6 class="mb-1">Jessica Taylor</h6>
                                <p class="text-muted small mb-2">@jessicat</p>
                                <p class="text-muted small mb-3">9 mutual friends</p>
                                <button class="btn btn-primary btn-sm w-100">
                                    View Profile
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 text-center">
                            <div class="card-body">
                                <img src="https://placehold.co/80x80" alt="Friend" class="rounded-circle mb-3"
                                    style="width: 80px; height: 80px" />
                                <h6 class="mb-1">Michael Lee</h6>
                                <p class="text-muted small mb-2">@michaell</p>
                                <p class="text-muted small mb-3">11 mutual friends</p>
                                <button class="btn btn-primary btn-sm w-100">
                                    View Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPostModalLabel">
                        <i class="fa fa-edit me-2"></i>Edit Post
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPostForm">
                        <input type="hidden" id="editPostId" />
                        <div class="mb-3">
                            <label for="editPostContent" class="form-label">Post Content</label>
                            <textarea class="form-control" id="editPostContent" rows="4" placeholder="What's on your mind?"
                                required></textarea>
                        </div>
                        <!-- Edit Images Preview -->
                        <div id="editImagesPreviewContainer" class="mb-3">
                            <label class="form-label">Images</label>
                            <div id="editImagesPreviewGrid" class="row g-2">
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    onclick="document.getElementById('editImageInput').click()">
                                    <i class="fa fa-plus me-1"></i>Add More Images
                                </button>
                                <input type="file" id="editImageInput" accept="image/*" class="d-none" multiple
                                    onchange="previewEditImages(this)" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="savePostEdit()">
                        <i class="fa fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Gallery Modal -->
    <div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-labelledby="imageGalleryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title text-white" id="imageGalleryModalLabel">
                        <span id="galleryImageCounter">1 / 1</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 position-relative" style="min-height: 500px;">
                    <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="galleryCarouselInner">
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const postImagesData = {
            @foreach($posts as $post)
                @if($post->images->count() > 1)
                                    {{ $post->id }}: [
                    @foreach($post->images as $image)
                        '{{ displayImage($image->image) }}'{{ !$loop->last ? ',' : '' }}
                    @endforeach
                                    ]{{ !$loop->last ? ',' : '' }}
                @endif
            @endforeach
                        };

        function openGallery(postId, startIndex) {
            const images = postImagesData[postId];
            if (!images || images.length === 0) return;

            const carouselInner = document.getElementById('galleryCarouselInner');
            const counter = document.getElementById('galleryImageCounter');

            carouselInner.innerHTML = '';

            images.forEach((imageUrl, index) => {
                const carouselItem = document.createElement('div');
                carouselItem.className = `carousel-item ${index === startIndex ? 'active' : ''}`;
                carouselItem.innerHTML = `
                                    <img src="${imageUrl}" class="d-block w-100" alt="Gallery Image ${index + 1}" 
                                        style="max-height: 70vh; object-fit: contain; margin: 0 auto;">
                                `;
                carouselInner.appendChild(carouselItem);
            });

            counter.textContent = `${startIndex + 1} / ${images.length}`;

            const carousel = document.getElementById('galleryCarousel');
            carousel.addEventListener('slid.bs.carousel', function (e) {
                const activeIndex = Array.from(carouselInner.children).indexOf(e.relatedTarget);
                counter.textContent = `${activeIndex + 1} / ${images.length}`;
            });
        }

        const postsData = {
            @foreach($posts as $post)
                                                    {{ $post->id }}: {
                    content: @json($post->content),
                    images: [
                        @foreach($post->images as $image)
                                                                                    {
                                id: {{ $image->id }},
                                url: '{{ displayImage($image->image) }}'
                            }{{ !$loop->last ? ',' : '' }}
                        @endforeach
                                                        ]
                }{{ !$loop->last ? ',' : '' }}
            @endforeach
                        };

        let editNewImages = [];
        let editExistingImages = [];
        let postHadImages = false;

        function editPost(postId, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            const post = postsData[postId];
            if (!post) {
                toastr.error('Post data not found');
                return;
            }

            document.getElementById('editPostId').value = postId;

            document.getElementById('editPostContent').value = post.content;

            const previewGrid = document.getElementById('editImagesPreviewGrid');
            previewGrid.innerHTML = '';
            editExistingImages = [];
            editNewImages = [];

            postHadImages = post.images && post.images.length > 0;

            if (post.images && post.images.length > 0) {
                post.images.forEach((image, index) => {
                    editExistingImages.push(image.id);
                    const col = document.createElement('div');
                    col.className = 'col-md-4 col-sm-6';
                    col.setAttribute('data-image-id', image.id);
                    col.innerHTML = `
                                        <div class="position-relative">
                                            <img src="${image.url}" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;" alt="Post Image">
                                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                                onclick="removeEditImage(${image.id}, 'existing', this)" title="Remove">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    `;
                    previewGrid.appendChild(col);
                });
            }

            const imagesContainer = document.getElementById('editImagesPreviewContainer');
            if (post.images && post.images.length > 0) {
                imagesContainer.style.display = 'block';
            } else {
                imagesContainer.style.display = 'block';
            }

            const modal = new bootstrap.Modal(document.getElementById('editPostModal'));
            modal.show();
        }

        function previewEditImages(input) {
            if (input.files && input.files.length > 0) {
                const previewGrid = document.getElementById('editImagesPreviewGrid');

                Array.from(input.files).forEach((file) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const col = document.createElement('div');
                            col.className = 'col-md-4 col-sm-6';
                            const tempId = 'new_' + Date.now() + '_' + Math.random();
                            col.setAttribute('data-image-id', tempId);
                            col.innerHTML = `
                                                <div class="position-relative">
                                                    <img src="${e.target.result}" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;" alt="New Image">
                                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                                        onclick="removeEditImage('${tempId}', 'new', this)" title="Remove">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            `;
                            previewGrid.appendChild(col);

                            editNewImages.push({
                                id: tempId,
                                file: file
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }

        function removeEditImage(imageId, type, buttonElement) {
            if (type === 'existing') {
                editExistingImages = editExistingImages.filter(id => id !== imageId);
            } else {
                editNewImages = editNewImages.filter(img => img.id !== imageId);
            }

            buttonElement.closest('.col-md-4').remove();
        }

        function savePostEdit() {
            const postId = document.getElementById('editPostId').value;
            const content = document.getElementById('editPostContent').value;

            if (!postId) {
                toastr.error('Post ID is missing');
                return;
            }

            if (!content.trim()) {
                toastr.error('Post content is required');
                return;
            }

            const formData = new FormData();
            formData.append('content', content);
            formData.append('_method', 'PUT');
            formData.append('_token', '{{ csrf_token() }}');

            if (postHadImages) {
                if (editExistingImages.length > 0) {
                    editExistingImages.forEach((imageId, index) => {
                        formData.append(`existing_images[${index}]`, imageId);
                    });
                } else {
                    formData.append('existing_images[]', '');
                }
            }

            editNewImages.forEach((img, index) => {
                formData.append(`images[${index}]`, img.file);
            });

            const saveBtn = event.target;
            const originalText = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Saving...';

            const baseUrl = '{{ route("posts.update", 999) }}';
            const updateUrl = baseUrl.replace(/\/999(\/|$)/, '/' + postId + '$1');
            $.ajax({
                url: updateUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editPostModal'));
                    modal.hide();

                    toastr.success('Post updated successfully');

                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function (xhr, status, error) {
                    let errorMessage = 'Error updating post. Please try again.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        errorMessage = errors.join('<br>');
                    }

                    toastr.error(errorMessage);
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalText;
                }
            });
        }

        $(document).on('click', '.pagination-link', function (e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (!url) return;

            const container = $('#profilePostsContainer');
            const paginationContainer = $('#postsPagination');

            container.html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            paginationContainer.html('');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    container.html(response.html);

                    paginationContainer.html(response.pagination);

                    if (response.postImagesData) {
                        Object.assign(postImagesData, response.postImagesData);
                    }

                    if (response.postsData) {
                        Object.assign(postsData, response.postsData);
                    }

                    $('html, body').animate({
                        scrollTop: $('#posts').offset().top - 100
                    }, 300);
                },
                error: function (xhr, status, error) {
                    container.html('<div class="col-12"><div class="alert alert-danger">Error loading posts. Please try again.</div></div>');
                }
            });
        });

    </script>
@endpush