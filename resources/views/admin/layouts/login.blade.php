@include('admin.inc.header')

<main class="py-4">
    <div class="container">
        @include('admin.inc.messages')
    </div>
    
    <div class="container">
        @yield('content')
    </div>
</main>

@include('admin.inc.footer')