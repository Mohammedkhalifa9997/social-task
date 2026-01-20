<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('front') }}/app.js"></script>
<script src="{{ asset('vendor/toastr/build/toastr.min.js') }}"></script>
<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    @if (session()->has('Success'))
        toastr.success('{{ session()->get('Success') }}');
    @endif
    @if (session()->has('Error'))
        toastr.error('{{ session()->get('Error') }}');
    @endif

    @if (session()->has('Warn'))
        toastr.warning('{{ session()->get('Warn') }}');
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif

</script>
<script>
        window.currentUserId = {{ auth()->id() }};

        @if($unreadNotificationsCount > 0)
            updateNotificationBadge({{ $unreadNotificationsCount }});
        @else
            const badge = document.getElementById("notificationBadge");
            if (badge) {
                badge.style.display = "none";
            }
        @endif
</script>
@stack('js')