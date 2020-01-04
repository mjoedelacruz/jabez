
<!DOCTYPE html>
<html lang="en">
  @include('inc.head')
  <body class="layout layout-header-fixed layout-sidebar-sticky layout-sidebar-collapsed">
  @include('inc.navheader')
    <div class="layout-main">
      @include('inc.sidebar')
       @include('inc.script')
      <div class="layout-content">
        <div class="layout-content-body">
          @yield('css')
          @yield('script')
          @yield('content')
        </div>
      </div> 
      @include('inc.footer')
    </div>
    @yield('modal')
  </body>
</html>