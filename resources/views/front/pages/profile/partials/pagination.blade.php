@if($posts->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Posts pagination">
            <ul class="pagination mb-0">
                @if($posts->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fa fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link pagination-link" href="{{ $posts->previousPageUrl() }}"
                            data-page="{{ $posts->currentPage() - 1 }}">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $start = max(1, $posts->currentPage() - 2);
                    $end = min($posts->lastPage(), $posts->currentPage() + 2);
                @endphp
                
                @if($start > 1)
                    <li class="page-item">
                        <a class="page-link pagination-link" href="{{ $posts->url(1) }}" data-page="1">1</a>
                    </li>
                    @if($start > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endif
                
                @foreach($posts->getUrlRange($start, $end) as $page => $url)
                    @if($page == $posts->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link pagination-link" href="{{ $url }}" data-page="{{ $page }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
                
                @if($end < $posts->lastPage())
                    @if($end < $posts->lastPage() - 1)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link pagination-link" href="{{ $posts->url($posts->lastPage()) }}" data-page="{{ $posts->lastPage() }}">{{ $posts->lastPage() }}</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if($posts->hasMorePages())
                    <li class="page-item">
                        <a class="page-link pagination-link" href="{{ $posts->nextPageUrl() }}"
                            data-page="{{ $posts->currentPage() + 1 }}">
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fa fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif