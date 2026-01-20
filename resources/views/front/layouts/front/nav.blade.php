<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="fa fa-users me-2"></i>Social App
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ activeLink('home') }}" href="{{ route('home') }}">
                        <i class="fa fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ activeLink('profile.*') }}" href="{{ route('profile.index') }}">
                        <i class="fa fa-user me-1"></i>Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="friends.html">
                        <i class="fa fa-users me-1"></i>Friends
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="notifications.html">
                        <i class="fa fa-bell me-1"></i>Notifications
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <!-- Notifications Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-light position-relative" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa fa-bell fs-5"></i>
                        @if($unreadNotificationsCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                id="notificationBadge">
                                {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                            </span>
                        @else
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                id="notificationBadge" style="display: none">
                                0
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
                        style="width: 350px; max-height: 500px; overflow-y: auto">
                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Notifications</span>
                            <a href="notifications.html" class="text-decoration-none small">View all</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <!-- Notification 1 -->
                        <li>
                            <a class="dropdown-item notification-item unread" href="#" id="notification-1">
                                <div class="d-flex align-items-start">
                                    <img src="https://placehold.co/40x40" alt="User" class="rounded-circle me-2"
                                        style="width: 40px; height: 40px" />
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="mb-0 small">
                                                    <strong>Jane Smith</strong> liked your post
                                                </p>
                                                <small class="text-muted">2 hours ago</small>
                                            </div>
                                            <img src="https://placehold.co/50x50" alt="Post" class="rounded ms-2"
                                                style="width: 50px; height: 50px; object-fit: cover" />
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <!-- Notification 2 -->
                        <li>
                            <a class="dropdown-item notification-item unread" href="#" id="notification-2">
                                <div class="d-flex align-items-start">
                                    <img src="https://placehold.co/40x40" alt="User" class="rounded-circle me-2"
                                        style="width: 40px; height: 40px" />
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="mb-0 small">
                                                    <strong>Mike Johnson</strong> commented on your
                                                    post
                                                </p>
                                                <small class="text-muted">5 hours ago</small>
                                            </div>
                                            <img src="https://placehold.co/50x50" alt="Post" class="rounded ms-2"
                                                style="width: 50px; height: 50px; object-fit: cover" />
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <!-- Notification 3 -->
                        <li>
                            <a class="dropdown-item notification-item unread" href="#" id="notification-3">
                                <div class="d-flex align-items-start">
                                    <img src="https://placehold.co/40x40" alt="User" class="rounded-circle me-2"
                                        style="width: 40px; height: 40px" />
                                    <div class="flex-grow-1">
                                        <div>
                                            <p class="mb-0 small">
                                                <strong>Sarah Williams</strong> sent you a friend
                                                request
                                            </p>
                                            <small class="text-muted">1 day ago</small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <!-- Notification 4 -->
                        <li>
                            <a class="dropdown-item notification-item" href="#" id="notification-4">
                                <div class="d-flex align-items-start">
                                    <img src="https://placehold.co/40x40" alt="User" class="rounded-circle me-2"
                                        style="width: 40px; height: 40px" />
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="mb-0 small">
                                                    <strong>David Brown</strong> shared your post
                                                </p>
                                                <small class="text-muted">2 days ago</small>
                                            </div>
                                            <img src="https://placehold.co/50x50" alt="Post" class="rounded ms-2"
                                                style="width: 50px; height: 50px; object-fit: cover" />
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a class="dropdown-item text-center text-primary fw-semibold" href="notifications.html">
                                View all notifications
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <img src="{{ displayImage(auth()->user()->image) }}" alt="User" class="rounded-circle me-2"
                            style="width: 32px; height: 32px" />
                        <span id="currentUserName">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="fa fa-user me-2"></i>My Profile
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="dropdown-item border-0 bg-transparent w-100 text-start"
                                    style="cursor: pointer;">
                                    <i class="fa fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>