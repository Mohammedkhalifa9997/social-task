@foreach($posts as $post)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0" data-post-id="{{ $post->id }}" data-post-owner-id="{{ $post->user_id }}">
            @if($post->images->count() > 0)
                <div class="position-relative post-gallery" data-post-id="{{ $post->id }}">
                    <div class="position-relative overflow-hidden"
                        style="height: 350px; border-radius: 8px 8px 0 0; background: #f8f9fa;">
                        <img src="{{ displayImage($post->images->first()->image) }}" alt="Post"
                            class="w-100 h-100 {{ $post->images->count() > 1 ? 'gallery-image' : '' }}"
                            style="object-fit: cover; {{ $post->images->count() > 1 ? 'cursor: pointer; transition: transform 0.3s ease, opacity 0.3s ease;' : 'transition: transform 0.3s ease;' }}"
                            @if($post->images->count() > 1) onclick="openGallery({{ $post->id }}, 0)" data-bs-toggle="modal"
                                data-bs-target="#imageGalleryModal"
                                onmouseover="this.style.transform='scale(1.03)'; this.style.opacity='0.95';"
                            onmouseout="this.style.transform='scale(1)'; this.style.opacity='1';" @else
                                onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'"
                            @endif />

                        @if($post->images->count() > 1)
                            <div class="position-absolute bottom-0 end-0 m-3" style="z-index: 10;">
                                <div class="badge bg-dark bg-opacity-90 px-3 py-2 rounded-pill shadow-lg gallery-badge"
                                    style="backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.15); cursor: pointer;"
                                    onclick="openGallery({{ $post->id }}, 0); event.stopPropagation();" data-bs-toggle="modal"
                                    data-bs-target="#imageGalleryModal">
                                    <i class="fa fa-images me-1"></i>
                                    <span class="fw-semibold">{{ $post->images->count() }} Photos</span>
                                </div>
                            </div>
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center gallery-overlay"
                                style="background: rgba(0,0,0,0); z-index: 5;">
                                <div class="text-white opacity-0" style="transition: opacity 0.3s ease;">
                                    <i class="fa fa-expand fs-1"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="position-absolute top-0 end-0 m-2" style="z-index: 15;">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-circle shadow-lg" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                style="width: 36px; height: 36px; padding: 0; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); transition: all 0.3s ease;"
                                onclick="event.stopPropagation()"
                                onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='rgba(255,255,255,0.95)';"
                                onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='rgba(255,255,255,0.9)';">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                <li>
                                    <a class="dropdown-item" href="#" onclick="editPost({{ $post->id }}, event)">
                                        <i class="fa fa-edit me-2"></i>Edit Post
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('posts.destroy', $post->id) }}" class="d-inline w-100">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start"
                                            style="cursor: pointer;" onclick="deletePost({{ $post->id }}, event)">
                                            <i class="fa fa-trash me-2"></i>Delete Post
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <div class="position-relative">
                    <div class="card-body pb-0">
                        <div class="position-absolute top-0 end-0 m-2">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false" style="width: 32px; height: 32px; padding: 0">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="editPost({{ $post->id }}, event)">
                                            <i class="fa fa-edit me-2"></i>Edit Post
                                        </a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('posts.destroy', $post->id) }}" class="d-inline w-100">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start"
                                                style="cursor: pointer;" onclick="deletePost({{ $post->id }}, event)">
                                                <i class="fa fa-trash me-2"></i>Delete Post
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card-body">
                <p class="card-text small">
                    {{ $post->content }}
                </p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                    <div class="d-flex gap-3">
                        <small class="text-muted">
                            <i class="far fa-heart"></i> {{ $post->likes_count }}
                        </small>
                        <small class="text-muted">
                            <i class="fa fa-comment"></i> {{ $post->comments_count }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach