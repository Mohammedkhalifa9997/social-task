const likeRequestsInProgress = new Set();

function toggleLike(button) {
    const btn = button.closest(".post-action-btn");
    const postId = btn.getAttribute("data-post-id");
    const icon = btn.querySelector("i");
    const likeCount = btn.querySelector(".like-count");

    if (!postId) {
        return;
    }

    if (likeRequestsInProgress.has(postId)) {
        return;
    }

    likeRequestsInProgress.add(postId);

    btn.disabled = true;
    const originalIcon = icon.className;

    const likeUrl = btn.getAttribute("data-like-url") || "/likes/toggle";

    const csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content") ||
        document.querySelector('input[name="_token"]')?.value;

    fetch(likeUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
        },
        body: JSON.stringify({
            post_id: postId,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            likeRequestsInProgress.delete(postId);
            btn.disabled = false;

            if ((data.status === 200 || data.success === true) && data.data) {
                if (likeCount) {
                    likeCount.textContent = data.data.likes_count || 0;
                }

                if (data.data.is_liked === true) {
                    btn.classList.add("liked");
                    icon.classList.remove("far");
                    icon.classList.add("fas");
                } else {
                    btn.classList.remove("liked");
                    icon.classList.remove("fas");
                    icon.classList.add("far");
                }
            } else {
                const errorMsg = data.message || "Failed to toggle like";
                if (typeof toastr !== "undefined") {
                    toastr.error(errorMsg);
                }
            }
        })
        .catch((error) => {
            likeRequestsInProgress.delete(postId);
            btn.disabled = false;
            if (typeof toastr !== "undefined") {
                toastr.error("An error occurred while toggling like");
            }
        });
}

function toggleComments(postId) {
    const commentsSection = document.getElementById(`comments-${postId}`);
    if (commentsSection) {
        if (
            commentsSection.style.display === "none" ||
            !commentsSection.style.display
        ) {
            commentsSection.style.display = "block";
        } else {
            commentsSection.style.display = "none";
        }
    }
}

function handleCommentSubmit(postId, event) {
    event.preventDefault();
    const input = document.getElementById(`comment-input-${postId}`);
    const commentText = input.value.trim();

    if (!commentText) return;

    input.value = "";
}

function handlePostSubmit(event) {
    event.preventDefault();
    const postContent = document.getElementById("postContent");
    const content = postContent.value.trim();

    if (!content) {
        alert("Please write something to post!");
        return;
    }

    postContent.value = "";
    removeImagePreview();
    removeFilePreview();
}

function previewImage(input) {
    const container = document.getElementById("imagePreviewContainer");
    const preview = document.getElementById("imagePreview");
    const fileInput = document.getElementById("fileInput");

    if (input.files && input.files[0]) {
        removeFilePreview();
        fileInput.value = "";

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            container.style.display = "block";
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewImages(input) {
    const container = document.getElementById("imagesPreviewContainer");
    const grid = document.getElementById("imagesPreviewGrid");
    const fileInput = document.getElementById("fileInput");

    if (input.files && input.files.length > 0) {
        removeFilePreview();
        fileInput.value = "";

        grid.innerHTML = "";

        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const colDiv = document.createElement("div");
                    colDiv.className = "col-6 col-md-4";

                    const imageWrapper = document.createElement("div");
                    imageWrapper.className = "position-relative";
                    imageWrapper.style.cssText =
                        "aspect-ratio: 1; overflow: hidden; border-radius: 8px;";

                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.className = "w-100 h-100";
                    img.style.cssText = "object-fit: cover; cursor: pointer;";

                    const removeBtn = document.createElement("button");
                    removeBtn.type = "button";
                    removeBtn.className =
                        "btn btn-sm btn-danger position-absolute";
                    removeBtn.style.cssText =
                        "top: 5px; right: 5px; padding: 2px 6px; z-index: 10;";
                    removeBtn.innerHTML = '<i class="fa fa-times"></i>';
                    removeBtn.onclick = function () {
                        removeImageFromPreview(colDiv, input, index);
                    };

                    imageWrapper.appendChild(img);
                    imageWrapper.appendChild(removeBtn);
                    colDiv.appendChild(imageWrapper);
                    grid.appendChild(colDiv);
                };
                reader.readAsDataURL(file);
            }
        });

        container.style.display = "block";
    }
}

function removeImageFromPreview(element, input, fileIndex) {
    element.remove();

    const dt = new DataTransfer();
    const files = Array.from(input.files);

    files.forEach((file, index) => {
        if (index !== fileIndex) {
            dt.items.add(file);
        }
    });

    input.files = dt.files;

    const grid = document.getElementById("imagesPreviewGrid");
    if (!grid || grid.children.length === 0) {
        const container = document.getElementById("imagesPreviewContainer");
        if (container) {
            container.style.display = "none";
        }
    }
}

function previewFile(input) {
    const container = document.getElementById("filePreviewContainer");
    const fileName = document.getElementById("fileName");
    const fileSize = document.getElementById("fileSize");
    const imageInput = document.getElementById("imageInput");

    if (input.files && input.files[0]) {
        removeImagePreview();
        imageInput.value = "";

        const file = input.files[0];
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        container.style.display = "block";
    }
}

function removeImagePreview() {
    const container = document.getElementById("imagePreviewContainer");
    const preview = document.getElementById("imagePreview");
    const imageInput = document.getElementById("imageInput");

    if (container) {
        container.style.display = "none";
    }
    if (preview) {
        preview.src = "";
    }
    if (imageInput) {
        imageInput.value = "";
    }

    const imagesContainer = document.getElementById("imagesPreviewContainer");
    const imagesGrid = document.getElementById("imagesPreviewGrid");
    if (imagesContainer) {
        imagesContainer.style.display = "none";
    }
    if (imagesGrid) {
        imagesGrid.innerHTML = "";
    }
}

function removeFilePreview() {
    const container = document.getElementById("filePreviewContainer");
    const fileInput = document.getElementById("fileInput");

    container.style.display = "none";
    fileInput.value = "";
}

function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + " " + sizes[i];
}

function updateNotificationBadge(count) {
    const badge = document.getElementById("notificationBadge");
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = "inline-block";
        } else {
            badge.style.display = "none";
        }
    }
}

function markNotificationAsRead(notificationId) {
    const notification = document.getElementById(
        `notification-${notificationId}`
    );
    if (notification) {
        notification.classList.remove("unread");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".post-action-btn").forEach((btn) => {
        if (btn.querySelector(".fa-heart")) {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                toggleLike(this);
            });
        }
    });

    document.querySelectorAll('[id^="comment-form-"]').forEach((form) => {
        form.addEventListener("submit", function (e) {
            const postId = this.id.replace("comment-form-", "");
            handleCommentSubmit(postId, e);
        });
    });
});

let currentPage = 1;
let isLoading = false;
let hasMorePosts = true;
let reachedEnd = false;

function forceHideLoader() {
    const loader = document.getElementById("postsLoadingIndicator");
    if (loader) {
        loader.style.cssText =
            "display: none !important; visibility: hidden !important; opacity: 0 !important; height: 0 !important; padding: 0 !important; margin: 0 !important;";
        loader.classList.add("d-none");
        loader.setAttribute("hidden", "true");
        if (loader.parentNode) {
            loader.remove();
        }
    }
}

if (typeof MutationObserver !== "undefined") {
    const loaderObserver = new MutationObserver(function (mutations) {
        if (reachedEnd) {
            const loader = document.getElementById("postsLoadingIndicator");
            if (
                loader &&
                (loader.style.display !== "none" ||
                    loader.offsetParent !== null)
            ) {
                forceHideLoader();
            }
        }
    });

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", function () {
            const loader = document.getElementById("postsLoadingIndicator");
            if (loader) {
                loaderObserver.observe(loader, {
                    attributes: true,
                    attributeFilter: ["style", "class"],
                    childList: false,
                    subtree: false,
                });
            }
        });
    } else {
        const loader = document.getElementById("postsLoadingIndicator");
        if (loader) {
            loaderObserver.observe(loader, {
                attributes: true,
                attributeFilter: ["style", "class"],
                childList: false,
                subtree: false,
            });
        }
    }
}

function initializeInfiniteScroll() {
    const postsContainer = document.getElementById("postsContainer");
    if (!postsContainer) return;

    if (!document.querySelector("#postsContainer")) return;

    if (typeof window.initialPage !== "undefined") {
        currentPage = window.initialPage;
    } else {
        currentPage = 1;
    }

    if (typeof window.hasMorePosts !== "undefined") {
        if (window.hasMorePosts === true || window.hasMorePosts === "true") {
            hasMorePosts = true;
        } else {
            hasMorePosts = false;
        }
    } else {
        hasMorePosts = true;
    }

    window.addEventListener("scroll", handleScroll);

    checkIfNeedMorePosts();
}

function handleScroll() {
    const hasMore = hasMorePosts === true || hasMorePosts === "true";
    if (isLoading || !hasMore) return;

    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;

    if (scrollTop + windowHeight >= documentHeight - 200) {
        loadMorePosts();
    }
}

function checkIfNeedMorePosts() {
    if (reachedEnd) {
        return;
    }

    const hasMore = hasMorePosts === true || hasMorePosts === "true";
    if (isLoading || !hasMore || reachedEnd) {
        return;
    }

    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;

    if (documentHeight < windowHeight * 1.5) {
        loadMorePosts();
    }
}

function loadMorePosts() {
    if (reachedEnd) {
        const loadingIndicator = document.getElementById(
            "postsLoadingIndicator"
        );
        if (loadingIndicator) {
            loadingIndicator.style.display = "none";
            loadingIndicator.style.visibility = "hidden";
            loadingIndicator.classList.add("d-none");
        }
        return;
    }

    const hasMore = hasMorePosts === true || hasMorePosts === "true";

    if (isLoading || !hasMore || reachedEnd) {
        return;
    }

    const hasMoreCheck = hasMorePosts === true || hasMorePosts === "true";
    if (!hasMoreCheck || reachedEnd) {
        return;
    }

    isLoading = true;
    const loadingIndicator = document.getElementById("postsLoadingIndicator");
    const endMessage = document.getElementById("postsEndMessage");

    if (loadingIndicator && hasMoreCheck && !reachedEnd) {
        loadingIndicator.style.display = "block";
        loadingIndicator.style.visibility = "visible";
        loadingIndicator.classList.remove("d-none");
    } else if (loadingIndicator) {
        loadingIndicator.style.display = "none";
        loadingIndicator.style.visibility = "hidden";
        loadingIndicator.classList.add("d-none");
    }
    if (endMessage) {
        endMessage.style.display = "none";
    }

    const nextPage = currentPage + 1;

    const postsUrl = window.postsUrl || "/";
    fetch(`${postsUrl}?page=${nextPage}`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            if (data.hasMore !== undefined) {
                hasMorePosts = data.hasMore === true || data.hasMore === "true";
            } else {
                hasMorePosts = !!(data.posts && data.posts.length > 0);
            }

            if (data.posts && data.posts.length > 0) {
                if (typeof window.postImagesData === "undefined") {
                    window.postImagesData = {};
                }
                data.posts.forEach((post) => {
                    if (post.images && post.images.length > 1) {
                        window.postImagesData[post.id] = post.images;
                    }
                });

                appendPosts(data.posts);
                currentPage = data.currentPage || nextPage;
            } else {
                hasMorePosts = false;
            }

            hasMorePosts = hasMorePosts === true || hasMorePosts === "true";

            if (loadingIndicator) {
                loadingIndicator.style.display = "none";
                loadingIndicator.style.visibility = "hidden";
                loadingIndicator.style.setProperty(
                    "display",
                    "none",
                    "important"
                );
                loadingIndicator.style.setProperty(
                    "visibility",
                    "hidden",
                    "important"
                );
                loadingIndicator.classList.add("d-none");
                loadingIndicator.setAttribute("hidden", "true");
            }

            if (!hasMorePosts) {
                reachedEnd = true;
                hasMorePosts = false;

                forceHideLoader();

                setTimeout(forceHideLoader, 10);
                setTimeout(forceHideLoader, 50);
                setTimeout(forceHideLoader, 100);

                if (endMessage) {
                    endMessage.style.display = "block";
                }
                return;
            } else {
                if (hasMorePosts === true || hasMorePosts === "true") {
                    setTimeout(checkIfNeedMorePosts, 100);
                }
            }
        })
        .catch((error) => {
            reachedEnd = true;
            hasMorePosts = false;

            if (loadingIndicator) {
                loadingIndicator.style.display = "none";
                loadingIndicator.style.visibility = "hidden";
                loadingIndicator.style.setProperty(
                    "display",
                    "none",
                    "important"
                );
                loadingIndicator.style.setProperty(
                    "visibility",
                    "hidden",
                    "important"
                );
                loadingIndicator.classList.add("d-none");
                loadingIndicator.setAttribute("hidden", "true");
            }
            if (endMessage) {
                endMessage.style.display = "block";
            }
        })
        .finally(() => {
            isLoading = false;
            if (reachedEnd) {
                forceHideLoader();
            } else {
                const loader = document.getElementById("postsLoadingIndicator");
                if (loader) {
                    loader.style.display = "none";
                    loader.style.visibility = "hidden";
                    loader.style.opacity = "0";
                    loader.style.setProperty("display", "none", "important");
                    loader.style.setProperty(
                        "visibility",
                        "hidden",
                        "important"
                    );
                    loader.classList.add("d-none");
                }
            }
        });
}

function appendPosts(posts) {
    const postsContainer = document.getElementById("postsContainer");
    if (!postsContainer) return;

    const loadingIndicator = document.getElementById("postsLoadingIndicator");
    const endMessage = document.getElementById("postsEndMessage");

    let loadingElement = null;
    let endElement = null;
    if (loadingIndicator) {
        loadingElement = loadingIndicator.cloneNode(true);
        loadingIndicator.remove();
    }
    if (endMessage) {
        endElement = endMessage.cloneNode(true);
        endMessage.remove();
    }

    posts.forEach((post) => {
        const postElement = createPostElement(post);
        postsContainer.appendChild(postElement);
    });

    if (loadingElement) {
        postsContainer.appendChild(loadingElement);
    }
    if (endElement) {
        postsContainer.appendChild(endElement);
    }

    initializePostEventListeners();
}

function createPostElement(post) {
    const postDiv = document.createElement("div");
    postDiv.className = "card shadow-sm border-0 post-card mb-4";
    postDiv.setAttribute("data-post-id", post.id);
    postDiv.setAttribute("data-post-owner-id", post.user.id);

    let imagesHtml = "";
    if (post.images && post.images.length > 0) {
        if (post.images.length > 1) {
            imagesHtml = `
                <div class="post-images-gallery mb-2 position-relative">
                    <div class="position-relative" style="height: 350px; border-radius: 8px; overflow: hidden; background: #f8f9fa;">
                        <img src="${escapeHtml(post.images[0])}" alt="Post" 
                            class="w-100 h-100 gallery-image" 
                            style="object-fit: cover; cursor: pointer; transition: transform 0.3s ease, opacity 0.3s ease;"
                            onclick="openGallery(${post.id}, 0)" 
                            data-bs-toggle="modal" data-bs-target="#imageGalleryModal"
                            onmouseover="this.style.transform='scale(1.03)'; this.style.opacity='0.95';"
                            onmouseout="this.style.transform='scale(1)'; this.style.opacity='1';" />
                        
                        <div class="position-absolute bottom-0 end-0 m-3" style="z-index: 10;">
                            <div class="badge bg-dark bg-opacity-90 px-3 py-2 rounded-pill shadow-lg gallery-badge"
                                style="backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.15); cursor: pointer; transition: all 0.3s ease;"
                                onclick="openGallery(${
                                    post.id
                                }, 0); event.stopPropagation();" 
                                data-bs-toggle="modal" data-bs-target="#imageGalleryModal"
                                onmouseover="this.style.transform='scale(1.05)'; this.style.backgroundColor='rgba(0,0,0,1)';"
                                onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='rgba(0,0,0,0.9)';">
                                <i class="fa fa-images me-1"></i>
                                <span class="fw-semibold">${
                                    post.images.length
                                } Photos</span>
                            </div>
                        </div>
                        
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center gallery-overlay"
                            style="background: rgba(0,0,0,0); z-index: 5; pointer-events: none; transition: all 0.3s ease;">
                            <div class="text-white opacity-0" style="transition: opacity 0.3s ease;">
                                <i class="fa fa-expand fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            if (typeof window.postImagesData === "undefined") {
                window.postImagesData = {};
            }
            window.postImagesData[post.id] = post.images;
        } else {
            imagesHtml = `
                <div class="post-images-gallery mb-2">
                    <img src="${escapeHtml(post.images[0])}" alt="Post image" 
                        class="post-image rounded w-100"
                        style="object-fit: cover; max-height: 500px; transition: transform 0.3s ease;"
                        onmouseover="this.style.transform='scale(1.02)'" 
                        onmouseout="this.style.transform='scale(1)'" />
                </div>
            `;
        }
    }

    postDiv.innerHTML = `
        <div class="post-header">
            <div class="d-flex align-items-center">
                <img src="${escapeHtml(
                    post.user.image || "defaults/user.png"
                )}" 
                     alt="${escapeHtml(post.user.name)}" 
                     class="rounded-circle me-3" 
                     style="width: 50px; height: 50px; object-fit: cover;">
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">${escapeHtml(
                        post.user.name
                    )}</h6>
                    <small class="text-muted">@${escapeHtml(
                        post.user.username ||
                            post.user.name.toLowerCase().replace(/\s+/g, "")
                    )} · ${post.created_at}</small>
                </div>
            </div>
        </div>
        <div class="post-content">
            <p class="mb-2">${escapeHtml(post.content)}</p>
            ${imagesHtml}
        </div>
        <div class="post-actions">
            <button class="post-action-btn ${
                post.is_liked ? "liked" : ""
            }" onclick="toggleLike(this)" data-post-id="${
        post.id
    }" data-like-url="${window.likeUrl || "/likes/toggle"}">
                <i class="${post.is_liked ? "fas" : "far"} fa-heart"></i>
                <span class="like-count ${
                    (post.likes_count || 0) > 0 ? "clickable-like-count" : ""
                }" ${
        (post.likes_count || 0) > 0
            ? `onclick="event.stopPropagation(); openLikedUsersModal(${post.id})" style="cursor: pointer; text-decoration: underline;" title="View who liked this post"`
            : ""
    } data-post-id="${post.id}">${post.likes_count || 0}</span>
            </button>
            <button class="post-action-btn" onclick="toggleComments(${
                post.id
            })">
                <i class="fa fa-comment"></i>
                <span>${post.comments_count || 0}</span>
            </button>

        </div>
        <div class="comment-section" id="comments-${
            post.id
        }" style="display: none;">
            <div class="px-3 pb-3">
                <form id="comment-form-${
                    post.id
                }" onsubmit="handleCommentSubmit(${post.id}, event)">
                    <div class="d-flex gap-2 mb-2">
                        <input type="text" class="form-control comment-input" 
                               placeholder="Write a comment..." id="comment-input-${
                                   post.id
                               }" required>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
                <div class="comments-list" id="comments-list-${post.id}">
                    <!-- Comments will be loaded from Blade or AJAX -->
                </div>
            </div>
        </div>
    `;

    return postDiv;
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

function initializePostEventListeners() {
    document.querySelectorAll(".post-action-btn").forEach((btn) => {
        if (
            btn.querySelector(".fa-heart") &&
            !btn.hasAttribute("data-listener-attached")
        ) {
            btn.setAttribute("data-listener-attached", "true");
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                toggleLike(this);
            });
        }
    });

    document.querySelectorAll('[id^="comment-form-"]').forEach((form) => {
        if (!form.hasAttribute("data-listener-attached")) {
            form.setAttribute("data-listener-attached", "true");
            form.addEventListener("submit", function (e) {
                const postId = this.id.replace("comment-form-", "");
                handleCommentSubmit(postId, e);
            });
        }
    });
}

window.toggleLike = toggleLike;
window.toggleComments = toggleComments;
window.handleCommentSubmit = handleCommentSubmit;
window.handlePostSubmit = handlePostSubmit;
window.updateNotificationBadge = updateNotificationBadge;
window.markNotificationAsRead = markNotificationAsRead;
window.previewImage = previewImage;
window.previewImages = previewImages;
window.previewFile = previewFile;
window.removeImagePreview = removeImagePreview;
window.removeFilePreview = removeFilePreview;
window.initializeInfiniteScroll = initializeInfiniteScroll;
