@include('front.layouts.common.head')
  <body>
    <!-- Navigation -->
    @include('front.layouts.front.nav')

    <!-- Main Content -->
  @yield('content')

    <!-- Bootstrap JS CDN -->
    @include('front.layouts.front.script')
  </body>
</html>
