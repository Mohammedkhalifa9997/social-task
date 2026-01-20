@extends('front.layouts.front.app')
@section('title', 'Home')
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
      background: rgba(0,0,0,0.2) !important;
    }
    .post-gallery:hover .gallery-overlay div {
      opacity: 1 !important;
    }
    .gallery-badge {
      transition: all 0.3s ease;
    }
    .gallery-badge:hover {
      transform: scale(1.05);
      background-color: rgba(0,0,0,1) !important;
    }
    .clickable-like-count {
      cursor: pointer;
      transition: color 0.2s ease;
    }
    .clickable-like-count:hover {
      color: #0d6efd !important;
      text-decoration: underline;
    }
    .clickable-comment-count {
      cursor: pointer;
      transition: color 0.2s ease;
    }
    .clickable-comment-count:hover {
      color: #0d6efd !important;
      text-decoration: underline;
    }
  </style>
@endpush
@section('content')
  <div class="container my-4">
    <div class="row">
      <!-- Left Sidebar -->
      <div class="col-lg-3 d-none d-lg-block mb-4">
        <div class="card shadow-sm border-0 sticky-top" style="top: 80px">
          <div class="card-body">
            <div class="text-center mb-3">
              <img src="{{ displayImage(auth()->user()->image) }}" alt="Profile" class="rounded-circle mb-2"
                style="width: 120px; height: 120px" />
              <h5 class="mb-1" id="sidebarUserName">{{auth()->user()->name}}</h5>
              <p class="text-muted small mb-0">{{"@" . auth()->user()->username}}</p>
            </div>
            <hr />
            <ul class="list-unstyled">
              <li class="mb-2">
                <a href="{{ route('profile.index') }}" class="text-decoration-none text-dark d-flex align-items-center">
                  <i class="fa fa-user me-2"></i>My Profile
                </a>
              </li>
              <li class="mb-2">
                <a href="#" class="text-decoration-none text-dark d-flex align-items-center">
                  <i class="fa fa-users me-2"></i>Friends
                </a>
              </li>
              <li class="mb-2">
                <a href="#" class="text-decoration-none text-dark d-flex align-items-center">
                  <i class="fa fa-bell me-2"></i>Notifications
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Main Feed -->
      <div class="col-lg-6 mb-4">
        <!-- Create Post Card -->
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-body">
            <form id="createPostForm" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
              @csrf
              <div class="d-flex mb-3">
                <img src="{{ displayImage(auth()->user()->image) }}" alt="User" class="rounded-circle me-3"
                  style="width: 50px; height: 50px" />
                <div class="flex-grow-1">
                  <textarea class="form-control border-0 bg-light" name="content" id="postContent" rows="3"
                    placeholder="What's on your mind?" required></textarea>
                </div>
              </div>
              <!-- Multiple Images Preview -->
              <div id="imagesPreviewContainer" class="mb-3" style="display: none">
                <div id="imagesPreviewGrid" class="row g-2">
                  <!-- Images will be dynamically added here -->
                </div>
              </div>
              <!-- File Preview -->
              <div id="filePreviewContainer" class="mb-3" style="display: none">
                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                  <div class="d-flex align-items-center">
                    <i class="fa fa-file fs-3 text-primary me-3"></i>
                    <div>
                      <div class="fw-semibold" id="fileName"></div>
                      <small class="text-muted" id="fileSize"></small>
                    </div>
                  </div>
                  <button type="button" class="btn btn-sm btn-danger" onclick="removeFilePreview()">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-3">
                  <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                    onclick="document.getElementById('imageInput').click()">
                    <i class="fa fa-image me-1"></i>Photos
                  </button>
                  <input type="file" id="imageInput" name="images[]" accept="image/*" class="d-none" multiple
                    onchange="previewImages(this)" />
                  <input type="file" id="fileInput" class="d-none" onchange="previewFile(this)" />
                </div>
                <button type="submit" class="btn btn-primary px-4">
                  <i class="fa fa-paper-plane me-1"></i>Post
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Posts Feed -->
        <div id="postsContainer">
          @forelse($posts as $post)
            <div class="card shadow-sm border-0 post-card mb-4" data-post-id="{{ $post->id }}"
              data-post-owner-id="{{ $post->user_id }}">
              <div class="post-header">
                <div class="d-flex align-items-center">
                  <img src="{{ displayImage($post->user->image) }}" alt="{{ $post->user->name }}"
                    class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;" />
                  <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">{{ $post->user->name }}</h6>
                    <small class="text-muted">{{ '@' . $post->user->username }} ·
                      {{ $post->created_at->diffForHumans() }}</small>
                  </div>
                </div>
              </div>
              <div class="post-content">
                <p class="mb-2">{{ $post->content }}</p>
                @if($post->images->count() > 0)
                  <!-- Multiple Images Gallery -->
                  <div class="post-images-gallery mb-2 position-relative">
                    @if($post->images->count() > 1)
                      <div class="position-relative" style="height: 350px; border-radius: 8px; overflow: hidden; background: #f8f9fa;">
                        <img src="{{ displayImage($post->images->first()->image) }}" alt="Post" 
                          class="w-100 h-100 gallery-image" 
                          style="object-fit: cover; cursor: pointer; transition: transform 0.3s ease, opacity 0.3s ease;"
                          onclick="openGallery({{ $post->id }}, 0)" 
                          data-bs-toggle="modal" data-bs-target="#imageGalleryModal"
                          onmouseover="this.style.transform='scale(1.03)'; this.style.opacity='0.95';"
                          onmouseout="this.style.transform='scale(1)'; this.style.opacity='1';" />
                        
                        <div class="position-absolute bottom-0 end-0 m-3" style="z-index: 10;">
                          <div class="badge bg-dark bg-opacity-90 px-3 py-2 rounded-pill shadow-lg gallery-badge"
                            style="backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.15); cursor: pointer; transition: all 0.3s ease;"
                            onclick="openGallery({{ $post->id }}, 0); event.stopPropagation();" 
                            data-bs-toggle="modal" data-bs-target="#imageGalleryModal"
                            onmouseover="this.style.transform='scale(1.05)'; this.style.backgroundColor='rgba(0,0,0,1)';"
                            onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='rgba(0,0,0,0.9)';">
                            <i class="fa fa-images me-1"></i>
                            <span class="fw-semibold">{{ $post->images->count() }} Photos</span>
                          </div>
                        </div>
                        
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center gallery-overlay"
                          style="background: rgba(0,0,0,0); z-index: 5; pointer-events: none; transition: all 0.3s ease;">
                          <div class="text-white opacity-0" style="transition: opacity 0.3s ease;">
                            <i class="fa fa-expand fs-1"></i>
                          </div>
                        </div>
                      </div>
                    @else
                      <img src="{{ displayImage($post->images->first()->image) }}" alt="Post image" 
                        class="post-image rounded w-100"
                        style="object-fit: cover; max-height: 500px; transition: transform 0.3s ease;"
                        onmouseover="this.style.transform='scale(1.02)'" 
                        onmouseout="this.style.transform='scale(1)'" />
                    @endif
                  </div>
                @endif
              </div>
              <div class="post-actions">
                <button class="post-action-btn {{ $post->isLikedBy(auth()->user()) ? 'liked' : '' }}" 
                  onclick="toggleLike(this, event)" 
                  data-post-id="{{ $post->id }}"
                  data-like-url="{{ route('likes.toggle') }}">
                  <i class="{{ $post->isLikedBy(auth()->user()) ? 'fas' : 'far' }} fa-heart"></i>
                  <span class="like-count {{ $post->likes_count > 0 ? 'clickable-like-count' : '' }}" 
                    @if($post->likes_count > 0)
                      onclick="event.stopPropagation(); event.preventDefault(); openLikedUsersModal({{ $post->id }})"
                      style="cursor: pointer; text-decoration: underline;"
                      title="View who liked this post"
                    @endif
                    data-post-id="{{ $post->id }}">
                    {{ $post->likes_count }}
                  </span>
                </button>
                <button class="post-action-btn" onclick="toggleComments({{ $post->id }}, event)">
                  <i class="fa fa-comment"></i>
                  <span class="comment-count {{ $post->comments_count > 0 ? 'clickable-comment-count' : '' }}" 
                    @if($post->comments_count > 0)
                      onclick="event.stopPropagation(); event.preventDefault(); openCommentsModal({{ $post->id }})"
                      style="cursor: pointer; text-decoration: underline;"
                      title="View comments"
                    @endif
                    data-post-id="{{ $post->id }}">
                    {{ $post->comments_count }}
                  </span>
                </button>
              </div>
              <div class="comment-section" id="comments-{{ $post->id }}" style="display: none">
                <div class="px-3 pb-3">
                  <form id="comment-form-{{ $post->id }}" onsubmit="handleCommentSubmit({{ $post->id }}, event)">
                    <div class="d-flex gap-2 mb-2">
                      <input type="text" class="form-control comment-input" placeholder="Write a comment..."
                        id="comment-input-{{ $post->id }}" required />
                      <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-paper-plane"></i>
                      </button>
                    </div>
                  </form>
                  <div class="comments-list" id="comments-list-{{ $post->id }}">
                    <!-- Comments will be loaded from Blade -->
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="text-center py-5">
              <p class="text-muted">No posts yet. Start following people to see their posts!</p>
            </div>
          @endforelse

          <!-- Loading indicator for infinite scroll -->
          <div id="postsLoadingIndicator" class="text-center py-4" style="display: none">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2 mb-0">Loading more posts...</p>
          </div>

          <!-- End of posts message -->
          <div id="postsEndMessage" class="text-center py-4" style="display: none">
            <p class="text-muted mb-0">
              <i class="fa fa-check-circle me-1"></i>You've reached the end of the feed
            </p>
          </div>
        </div>
      </div>

      <!-- Right Sidebar -->
      <div class="col-lg-3 mb-4">
        <!-- Suggested Friends -->
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white border-0 pb-0">
            <h6 class="fw-bold mb-0">Suggested Friends</h6>
          </div>
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <img src="https://placehold.co/40x40" alt="Friend" class="rounded-circle me-2"
                style="width: 40px; height: 40px" />
              <div class="flex-grow-1">
                <div class="fw-semibold small">Jane Smith</div>
                <div class="text-muted small">12 mutual friends</div>
              </div>
              <button class="btn btn-sm btn-primary">Add</button>
            </div>
            <div class="d-flex align-items-center mb-3">
              <img src="https://placehold.co/40x40" alt="Friend" class="rounded-circle me-2"
                style="width: 40px; height: 40px" />
              <div class="flex-grow-1">
                <div class="fw-semibold small">Mike Johnson</div>
                <div class="text-muted small">8 mutual friends</div>
              </div>
              <button class="btn btn-sm btn-primary">Add</button>
            </div>
            <div class="d-flex align-items-center">
              <img src="https://placehold.co/40x40" alt="Friend" class="rounded-circle me-2"
                style="width: 40px; height: 40px" />
              <div class="flex-grow-1">
                <div class="fw-semibold small">Sarah Williams</div>
                <div class="text-muted small">5 mutual friends</div>
              </div>
              <button class="btn btn-sm btn-primary">Add</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Image Gallery Modal -->
  <div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-labelledby="imageGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-dark">
        <div class="modal-header border-secondary">
          <h5 class="modal-title text-white" id="imageGalleryModalLabel">
            <span id="galleryImageCounter">1 / 1</span>
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0 position-relative" style="min-height: 500px;">
          <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner" id="galleryCarouselInner">
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Liked Users Modal -->
  <div class="modal fade" id="likedUsersModal" tabindex="-1" aria-labelledby="likedUsersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="likedUsersModalLabel">People who liked this post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
          <div id="likedUsersList" class="d-flex flex-column gap-3">
            <!-- Liked users will be loaded here -->
            <div id="likedUsersLoading" class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Comments Modal -->
  <div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="commentsModalLabel">Comments</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
          <div id="commentsModalList" class="d-flex flex-column gap-3 mb-3">
            <!-- Comments will be loaded here -->
            <div id="commentsModalLoading" class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
          <div class="border-top pt-3">
            <form id="commentsModalForm" onsubmit="handleModalCommentSubmit(event)">
              <div class="d-flex gap-2">
                <input type="text" class="form-control" placeholder="Write a comment..." id="commentsModalInput" required />
                <button type="submit" class="btn btn-primary btn-sm">
                  <i class="fa fa-paper-plane"></i>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('js')
  <script>
    window.postImagesData = {
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
      const images = window.postImagesData && window.postImagesData[postId] ? window.postImagesData[postId] : null;
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

    window.initialPage = {{ $posts->currentPage() ?? 1 }};
    window.hasMorePosts = {{ $posts->hasMorePages() ? 'true' : 'false' }};
    window.postsUrl = '{{ route("home") }}';
    window.likeUrl = '{{ route("likes.toggle") }}';
    window.likedUsersUrl = '{{ route("likes.users") }}';
    window.commentUrl = '{{ route("comments.store") }}';
    window.commentsUrl = '{{ route("comments.index") }}';
    window.currentPostId = null;
    
    function openLikedUsersModal(postId) {
      const modal = new bootstrap.Modal(document.getElementById('likedUsersModal'));
      const usersList = document.getElementById('likedUsersList');
      const loadingIndicator = document.getElementById('likedUsersLoading');
      
      usersList.innerHTML = '';
      if (loadingIndicator) {
        loadingIndicator.style.display = 'block';
        usersList.appendChild(loadingIndicator);
      } else {
        usersList.innerHTML = `
          <div id="likedUsersLoading" class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        `;
      }
      
      modal.show();
      
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value;
      
      fetch(`${window.likedUsersUrl}?post_id=${postId}`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrfToken,
        }
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        const loadingEl = document.getElementById('likedUsersLoading');
        if (loadingEl) {
          loadingEl.style.display = 'none';
        }
        
        if (data.status === 200 && data.data && data.data.users) {
          const users = data.data.users;
          
          if (users.length === 0) {
            usersList.innerHTML = `
              <div class="text-center py-4">
                <p class="text-muted mb-0">No one has liked this post yet.</p>
              </div>
            `;
            return;
          }
          
          usersList.innerHTML = users.map(user => {
            let actionButton = '';
            if (user.is_current_user) {
              actionButton = '<span class="badge bg-secondary">You</span>';
            } else if (user.is_friend) {
              actionButton = '<span class="badge bg-success">Friend</span>';
            } else if (user.connection_status === 'pending') {
              actionButton = '<span class="badge bg-warning">Request Sent</span>';
            } else {
              actionButton = '<button class="btn btn-sm btn-primary" onclick="addFriend(' + user.id + ', this)">Add Friend</button>';
            }
            
            return `
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <img src="${user.image}" alt="${user.name}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                  <div>
                    <div class="fw-semibold">${user.name}</div>
                    <small class="text-muted">@${user.username}</small>
                  </div>
                </div>
                ${actionButton}
              </div>
            `;
          }).join('');
        } else {
          usersList.innerHTML = `
            <div class="text-center py-4">
              <p class="text-danger mb-0">Failed to load liked users.</p>
            </div>
          `;
        }
      })
      .catch(error => {
        const loadingEl = document.getElementById('likedUsersLoading');
        if (loadingEl) {
          loadingEl.style.display = 'none';
        }
        usersList.innerHTML = `
          <div class="text-center py-4">
            <p class="text-danger mb-0">An error occurred while loading liked users.</p>
          </div>
        `;
      });
    }
    
    function addFriend(userId, buttonElement) {
      
      if (buttonElement) {
        buttonElement.disabled = true;
        buttonElement.textContent = 'Request Sent';
        buttonElement.classList.remove('btn-primary');
        buttonElement.classList.add('btn-secondary');
      }
      
      if (typeof toastr !== 'undefined') {
        toastr.info('Friend request functionality will be implemented. Connect this to your connection/request endpoint.');
      }
      
      /*
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value;
      
      fetch('/connections/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          receiver_id: userId,
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 200 || data.success === true) {
          if (buttonElement) {
            buttonElement.textContent = 'Request Sent';
            buttonElement.classList.remove('btn-primary');
            buttonElement.classList.add('btn-secondary');
          }
          if (typeof toastr !== 'undefined') {
            toastr.success('Friend request sent successfully');
          }
        } else {
          if (buttonElement) {
            buttonElement.disabled = false;
          }
          if (typeof toastr !== 'undefined') {
            toastr.error(data.message || 'Failed to send friend request');
          }
        }
      })
      .catch(error => {
        if (buttonElement) {
          buttonElement.disabled = false;
        }
        if (typeof toastr !== 'undefined') {
          toastr.error('An error occurred while sending friend request');
        }
      });
      */
    }
    
    function toggleLike(buttonElement, event) {
      if (event) {
        event.stopPropagation();
        event.preventDefault();
      }
      
      if (event && event.target && event.target.classList.contains('like-count')) {
        return;
      }
      
      const postId = buttonElement.getAttribute('data-post-id');
      const likeUrl = buttonElement.getAttribute('data-like-url') || window.likeUrl;
      const heartIcon = buttonElement.querySelector('i');
      const likeCountSpan = buttonElement.querySelector('.like-count');
      
      if (buttonElement.disabled) {
        return;
      }
      
      buttonElement.disabled = true;
      
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value;
      
      fetch(likeUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({
          post_id: parseInt(postId),
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 200 && data.data) {
          const { likes_count, is_liked } = data.data;
          
          if (likeCountSpan) {
            likeCountSpan.textContent = likes_count;
            
            if (likes_count > 0) {
              likeCountSpan.classList.add('clickable-like-count');
              likeCountSpan.style.cursor = 'pointer';
              likeCountSpan.style.textDecoration = 'underline';
              likeCountSpan.setAttribute('title', 'View who liked this post');
              likeCountSpan.setAttribute('onclick', `event.stopPropagation(); openLikedUsersModal(${postId})`);
            } else {
              likeCountSpan.classList.remove('clickable-like-count');
              likeCountSpan.style.cursor = '';
              likeCountSpan.style.textDecoration = '';
              likeCountSpan.removeAttribute('title');
              likeCountSpan.removeAttribute('onclick');
            }
          }
          
          if (heartIcon) {
            if (is_liked) {
              heartIcon.classList.remove('far');
              heartIcon.classList.add('fas');
              buttonElement.classList.add('liked');
            } else {
              heartIcon.classList.remove('fas');
              heartIcon.classList.add('far');
              buttonElement.classList.remove('liked');
            }
          }
          
          if (typeof toastr !== 'undefined') {
            toastr.success(data.message || 'Like updated successfully');
          }
        } else {
          if (typeof toastr !== 'undefined') {
            toastr.error(data.message || 'Failed to update like');
          }
        }
      })
      .catch(error => {
        if (typeof toastr !== 'undefined') {
          toastr.error('An error occurred while updating like');
        }
      })
      .finally(() => {
        buttonElement.disabled = false;
      });
    }

    function toggleComments(postId, event) {
      if (event) {
        if (event.target && event.target.classList.contains('comment-count')) {
          return;
        }
        event.stopPropagation();
        event.preventDefault();
      }
      
      const commentSection = document.getElementById(`comments-${postId}`);
      const commentsList = document.getElementById(`comments-list-${postId}`);
      
      if (!commentSection) {
        return;
      }
      
      if (commentSection.style.display === 'none' || !commentSection.style.display) {
        commentSection.style.display = 'block';
        
        if (commentsList) {
          loadComments(postId, commentsList);
        } else {
        }
      } else {
        commentSection.style.display = 'none';
      }
    }

    function loadComments(postId, container) {
      if (!container) {
        return;
      }
      
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value;
      
      container.innerHTML = '<div class="text-center py-2"><div class="spinner-border spinner-border-sm text-primary" role="status"></div></div>';
      
      fetch(`${window.commentsUrl}?post_id=${postId}`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrfToken,
        }
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        
        let comments = null;
        if (data.status === 200 && data.data) {
          if (data.data.comments) {
            comments = data.data.comments;
          } else if (Array.isArray(data.data)) {
            comments = data.data;
          }
        } else if (data.comments) {
          comments = data.comments;
        } else if (Array.isArray(data)) {
          comments = data;
        }
        
        if (comments && Array.isArray(comments)) {
          if (comments.length === 0) {
            container.innerHTML = '<p class="text-muted text-center small mb-0">No comments yet. Be the first to comment!</p>';
            return;
          }
          
          container.innerHTML = comments.map(comment => {
            const user = comment.user || {};
            const userName = user.name || comment.user_name || 'Unknown';
            const userUsername = user.username || comment.user_username || '';
            const userImage = user.image || comment.user_image || '/defaults/user.png';
            const content = comment.content || comment.comment || '';
            const createdAt = comment.created_at || comment.created || 'Just now';
            
            return `
              <div class="d-flex gap-2 mb-3">
                <img src="${userImage}" alt="${userName}" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover; flex-shrink: 0;">
                <div class="flex-grow-1">
                  <div class="bg-light rounded p-2 mb-1">
                    <div class="fw-semibold small mb-1">${userName}${userUsername ? ' <small class="text-muted fw-normal">@' + userUsername + '</small>' : ''}</div>
                    <div class="small">${content}</div>
                  </div>
                  <small class="text-muted">${createdAt}</small>
                </div>
              </div>
            `;
          }).join('');
        } else {
          container.innerHTML = '<p class="text-danger text-center small mb-0">Failed to load comments. Response: ' + JSON.stringify(data).substring(0, 100) + '...</p>';
        }
      })
      .catch(error => {
        container.innerHTML = '<p class="text-danger text-center small mb-0">An error occurred while loading comments. Please try again.</p>';
      });
    }

    function handleCommentSubmit(postId, event) {
      event.preventDefault();
      
      const input = document.getElementById(`comment-input-${postId}`);
      const content = input.value.trim();
      
      if (!content) {
        return;
      }
      
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value;
      
      const submitButton = event.target.querySelector('button[type="submit"]');
      if (submitButton) {
        submitButton.disabled = true;
      }
      
      fetch(window.commentUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({
          post_id: parseInt(postId),
          content: content,
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 200 && data.data) {
          const { comment, comments_count } = data.data;
          
          input.value = '';
          
          const postCard = document.querySelector(`.post-card[data-post-id="${postId}"]`) || 
                          document.querySelector(`[data-post-id="${postId}"]`);
          if (postCard) {
            const allButtons = postCard.querySelectorAll('.post-action-btn');
            let commentButton = null;
            for (let btn of allButtons) {
              const icon = btn.querySelector('.fa-comment');
              if (icon) {
                commentButton = btn;
                break;
              }
            }
            
            if (commentButton) {
              const commentCountSpan = commentButton.querySelector('.comment-count');
              if (commentCountSpan) {
                commentCountSpan.textContent = comments_count;
                if (comments_count > 0) {
                  commentCountSpan.classList.add('clickable-comment-count');
                  commentCountSpan.style.cursor = 'pointer';
                  commentCountSpan.style.textDecoration = 'underline';
                  commentCountSpan.setAttribute('title', 'View comments');
                  commentCountSpan.setAttribute('onclick', `event.stopPropagation(); event.preventDefault(); openCommentsModal(${postId})`);
                }
              } else {
                const spans = commentButton.querySelectorAll('span');
                for (let span of spans) {
                  if (span.textContent && !isNaN(parseInt(span.textContent.trim()))) {
                    span.textContent = comments_count;
                    span.className = 'comment-count' + (comments_count > 0 ? ' clickable-comment-count' : '');
                    if (comments_count > 0) {
                      span.style.cursor = 'pointer';
                      span.style.textDecoration = 'underline';
                      span.setAttribute('title', 'View comments');
                      span.setAttribute('onclick', `event.stopPropagation(); event.preventDefault(); openCommentsModal(${postId})`);
                    }
                    break;
                  }
                }
              }
            }
          }
          
          const commentSection = document.getElementById(`comments-${postId}`);
          if (commentSection) {
            commentSection.style.display = 'block';
            setTimeout(() => {
              commentSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
          }
          
          const commentsList = document.getElementById(`comments-list-${postId}`);
          if (commentsList) {
            setTimeout(() => {
              loadComments(postId, commentsList);
            }, 100);
          } 
          
          if (typeof toastr !== 'undefined') {
            toastr.success(data.message || 'Comment added successfully');
          }
        } else {
          if (typeof toastr !== 'undefined') {
            toastr.error(data.message || 'Failed to add comment');
          }
        }
      })
      .catch(error => {
        if (typeof toastr !== 'undefined') {
          toastr.error('An error occurred while adding comment');
        }
      })
      .finally(() => {
        if (submitButton) {
          submitButton.disabled = false;
        }
      });
    }

    function openCommentsModal(postId) {
      window.currentPostId = postId;
      const modal = new bootstrap.Modal(document.getElementById('commentsModal'));
      const commentsList = document.getElementById('commentsModalList');
      const form = document.getElementById('commentsModalForm');
      const input = document.getElementById('commentsModalInput');
      
      if (form) {
        form.setAttribute('data-post-id', postId);
      }
      if (input) {
        input.value = '';
      }
      
      commentsList.innerHTML = `
        <div id="commentsModalLoading" class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      `;
      
      modal.show();
      
      loadCommentsModal(postId);
    }

    function loadCommentsModal(postId) {
      const commentsList = document.getElementById('commentsModalList');
      if (!commentsList) {
        return;
      }
      
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value;
      
      commentsList.innerHTML = `
        <div id="commentsModalLoading" class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      `;
      
      fetch(`${window.commentsUrl}?post_id=${postId}`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrfToken,
        }
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        const loadingEl = document.getElementById('commentsModalLoading');
        if (loadingEl) {
          loadingEl.remove();
        }
        
        let comments = null;
        if (data.status === 200 && data.data) {
          if (data.data.comments) {
            comments = data.data.comments;
          } else if (Array.isArray(data.data)) {
            comments = data.data;
          }
        } else if (data.comments) {
          comments = data.comments;
        } else if (Array.isArray(data)) {
          comments = data;
        }
        
        if (comments && Array.isArray(comments)) {
          if (comments.length === 0) {
            commentsList.innerHTML = '<p class="text-muted text-center mb-0">No comments yet. Be the first to comment!</p>';
            return;
          }
          
          commentsList.innerHTML = comments.map(comment => {
            const user = comment.user || {};
            const userName = user.name || comment.user_name || 'Unknown';
            const userUsername = user.username || comment.user_username || '';
            const userImage = user.image || comment.user_image || '/defaults/user.png';
            const content = comment.content || comment.comment || '';
            const createdAt = comment.created_at || comment.created || 'Just now';
            
            return `
              <div class="d-flex gap-3 mb-3">
                <img src="${userImage}" alt="${userName}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; flex-shrink: 0;">
                <div class="flex-grow-1">
                  <div class="bg-light rounded p-3 mb-1">
                    <div class="fw-semibold mb-1">${userName}${userUsername ? ' <small class="text-muted fw-normal">@' + userUsername + '</small>' : ''}</div>
                    <div>${content}</div>
                  </div>
                  <small class="text-muted">${createdAt}</small>
                </div>
              </div>
            `;
          }).join('');
        } else {
          commentsList.innerHTML = '<p class="text-danger text-center mb-0">Failed to load comments. Response: ' + JSON.stringify(data).substring(0, 100) + '...</p>';
        }
      })
      .catch(error => {
        const loadingEl = document.getElementById('commentsModalLoading');
        if (loadingEl) {
          loadingEl.remove();
        }
        commentsList.innerHTML = '<p class="text-danger text-center mb-0">An error occurred while loading comments. Please try again.</p>';
      });
    }

    function handleModalCommentSubmit(event) {
      event.preventDefault();
      
      const form = event.target;
      const postId = form.getAttribute('data-post-id') || window.currentPostId;
      const input = document.getElementById('commentsModalInput');
      const content = input.value.trim();
      
      if (!content || !postId) {
        return;
      }
      
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                       document.querySelector('input[name="_token"]')?.value;
      
      const submitButton = form.querySelector('button[type="submit"]');
      if (submitButton) {
        submitButton.disabled = true;
      }
      
      fetch(window.commentUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({
          post_id: parseInt(postId),
          content: content,
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 200 && data.data) {
          const { comment, comments_count } = data.data;
          
          input.value = '';
          
          const postCard = document.querySelector(`.post-card[data-post-id="${postId}"]`) || 
                          document.querySelector(`[data-post-id="${postId}"]`);
          if (postCard) {
            const allButtons = postCard.querySelectorAll('.post-action-btn');
            let commentButton = null;
            for (let btn of allButtons) {
              const icon = btn.querySelector('.fa-comment');
              if (icon) {
                commentButton = btn;
                break;
              }
            }
            
            if (commentButton) {
              const commentCountSpan = commentButton.querySelector('.comment-count');
              if (commentCountSpan) {
                commentCountSpan.textContent = comments_count;
                if (comments_count > 0) {
                  commentCountSpan.classList.add('clickable-comment-count');
                  commentCountSpan.style.cursor = 'pointer';
                  commentCountSpan.style.textDecoration = 'underline';
                  commentCountSpan.setAttribute('title', 'View comments');
                  commentCountSpan.setAttribute('onclick', `event.stopPropagation(); event.preventDefault(); openCommentsModal(${postId})`);
                }
              } else {
                const spans = commentButton.querySelectorAll('span');
                for (let span of spans) {
                  if (span.textContent && !isNaN(parseInt(span.textContent.trim()))) {
                    span.textContent = comments_count;
                    span.className = 'comment-count' + (comments_count > 0 ? ' clickable-comment-count' : '');
                    if (comments_count > 0) {
                      span.style.cursor = 'pointer';
                      span.style.textDecoration = 'underline';
                      span.setAttribute('title', 'View comments');
                      span.setAttribute('onclick', `event.stopPropagation(); event.preventDefault(); openCommentsModal(${postId})`);
                    }
                    break;
                  }
                }
              }
            }
          }
          
          loadCommentsModal(postId);
          
          const commentSection = document.getElementById(`comments-${postId}`);
          if (commentSection && commentSection.style.display !== 'none') {
            const commentsList = document.getElementById(`comments-list-${postId}`);
            if (commentsList) {
              loadComments(postId, commentsList);
            }
          }
          
          if (typeof toastr !== 'undefined') {
            toastr.success(data.message || 'Comment added successfully');
          }
        } else {
          if (typeof toastr !== 'undefined') {
            toastr.error(data.message || 'Failed to add comment');
          }
        }
      })
      .catch(error => {
        if (typeof toastr !== 'undefined') {
          toastr.error('An error occurred while adding comment');
        }
      })
      .finally(() => {
        if (submitButton) {
          submitButton.disabled = false;
        }
      });
    }

    window.openLikedUsersModal = openLikedUsersModal;
    window.addFriend = addFriend;
    window.toggleLike = toggleLike;
    window.toggleComments = toggleComments;
    window.handleCommentSubmit = handleCommentSubmit;
    window.openCommentsModal = openCommentsModal;
    window.handleModalCommentSubmit = handleModalCommentSubmit;
    
    initializeInfiniteScroll();
  </script>
@endpush